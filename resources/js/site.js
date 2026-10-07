/*
 * MUMIAS VIPERS CBO — public website behaviour
 * Vanilla JS, no dependencies. Progressive enhancement only: every feature
 * here has a working, accessible no-JS baseline in CSS/HTML.
 */
(function () {
    'use strict';

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function $(sel, ctx) { return (ctx || document).querySelector(sel); }
    function $$(sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); }

    var FOCUSABLE = 'a[href], button:not([disabled]), input, select, textarea, [tabindex]:not([tabindex="-1"])';

    /* ------------------------------------------------------------------
     * Shared focus dialog — ESC to close, Tab cycling, scroll lock and
     * focus restore. Used by the mobile drawer and the gallery lightbox;
     * per-feature keys (e.g. lightbox arrows) plug in via onKey.
     * ------------------------------------------------------------------ */
    function cycleFocus(scope, selector, e) {
        if (e.key !== 'Tab') return;
        var items = $$(selector, scope).filter(function (el) { return el.offsetParent !== null; });
        if (!items.length) return;
        var first = items[0];
        var last = items[items.length - 1];
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    }

    /* Overlay = element toggled with .is-open; scope = element queried for
     * tabbable items. focusFirst() decides what gets focus on open.
     * onChange() fires after every open/close (covers ESC as well). */
    function makeFocusDialog(overlay, scope, selector, focusFirst, onKey, onChange) {
        var lastFocused = null;

        function open() {
            lastFocused = document.activeElement;
            overlay.classList.add('is-open');
            document.body.classList.add('v-no-scroll');
            document.addEventListener('keydown', onKeydown);
            var first = focusFirst();
            if (first) first.focus();
            if (onChange) onChange(true);
        }

        function close() {
            overlay.classList.remove('is-open');
            document.body.classList.remove('v-no-scroll');
            document.removeEventListener('keydown', onKeydown);
            if (lastFocused && lastFocused.focus) lastFocused.focus();
            if (onChange) onChange(false);
        }

        function onKeydown(e) {
            if (e.key === 'Escape') { close(); return; }
            if (onKey && onKey(e)) return;
            cycleFocus(scope, selector, e);
        }

        return { open: open, close: close };
    }

    /* ------------------------------------------------------------------
     * Sticky nav shadow
     * ------------------------------------------------------------------ */
    function initNav() {
        var nav = $('.v-nav');
        if (!nav) return;
        var apply = function () { nav.classList.toggle('is-stuck', window.scrollY > 8); };
        apply();
        window.addEventListener('scroll', apply, { passive: true });
    }

    /* ------------------------------------------------------------------
     * Mobile drawer — focus trap, ESC, scrim click, scroll lock
     * ------------------------------------------------------------------ */
    function initMobile() {
        var toggle = $('.v-nav__toggle');
        var drawer = $('.v-mobile');
        if (!toggle || !drawer) return;

        var panel = $('.v-mobile__panel', drawer);
        var scrim = $('.v-mobile__scrim', drawer);
        var closeBtn = $('.v-mobile__close', drawer);

        var dialog = makeFocusDialog(drawer, panel, FOCUSABLE, function () {
            return $(FOCUSABLE, panel);
        }, null, function (isOpen) {
            toggle.setAttribute('aria-expanded', String(isOpen));
        });
        var open = dialog.open;
        var close = dialog.close;

        toggle.addEventListener('click', function () {
            drawer.classList.contains('is-open') ? close() : open();
        });
        if (scrim) scrim.addEventListener('click', close);
        if (closeBtn) closeBtn.addEventListener('click', close);

        // Close when a link inside the drawer is followed
        $$('a', panel).forEach(function (link) { link.addEventListener('click', close); });
    }

    /* ------------------------------------------------------------------
     * Mobile submenus (accordion behaviour)
     * ------------------------------------------------------------------ */
    function initSubmenus() {
        $$('[data-submenu-toggle]').forEach(function (btn) {
            var sub = document.getElementById(btn.getAttribute('aria-controls'));
            if (!sub) return;

            btn.addEventListener('click', function () {
                var isOpen = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', String(!isOpen));
                sub.classList.toggle('is-open', !isOpen);
            });
        });
    }

    /* ------------------------------------------------------------------
     * Desktop dropdowns — click/tap support plus keyboard control
     * ------------------------------------------------------------------ */
    function initDropdowns() {
        $$('[data-dropdown]').forEach(function (item) {
            var trigger = $('[data-dropdown-trigger]', item);
            var menu = $('[data-dropdown-menu]', item);
            if (!trigger || !menu) return;

            function setOpen(open) {
                trigger.setAttribute('aria-expanded', String(open));
                menu.classList.toggle('is-open', open);
            }

            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                setOpen(trigger.getAttribute('aria-expanded') !== 'true');
            });

            item.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') { setOpen(false); trigger.focus(); }
            });

            // Close when focus leaves the item
            item.addEventListener('focusout', function (e) {
                if (!item.contains(e.relatedTarget)) setOpen(false);
            });
        });

        document.addEventListener('click', function (e) {
            $$('[data-dropdown]').forEach(function (item) {
                if (item.contains(e.target)) return;
                var trigger = $('[data-dropdown-trigger]', item);
                var menu = $('[data-dropdown-menu]', item);
                if (trigger && trigger.getAttribute('aria-expanded') === 'true') {
                    trigger.setAttribute('aria-expanded', 'false');
                    if (menu) menu.classList.remove('is-open');
                }
            });
        });
    }

    /* ------------------------------------------------------------------
     * Scroll reveals
     * ------------------------------------------------------------------ */
    function initReveals() {
        var items = $$('.v-reveal');
        if (!items.length) return;

        // No observer support, or reduced motion — show everything at once
        if (!('IntersectionObserver' in window) || reduceMotion) {
            items.forEach(function (el) { el.classList.add('is-in'); });
            return;
        }

        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-in');
                io.unobserve(entry.target);
            });
        }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

        items.forEach(function (el) { io.observe(el); });
    }

    /* ------------------------------------------------------------------
     * Count-up for verified impact figures
     * ------------------------------------------------------------------ */
    function initCounters() {
        var nums = $$('[data-count]');
        if (!nums.length) return;

        if (!('IntersectionObserver' in window) || reduceMotion) {
            nums.forEach(function (el) { el.textContent = el.getAttribute('data-count'); });
            return;
        }

        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                io.unobserve(entry.target);

                var el = entry.target;
                var target = parseFloat(el.getAttribute('data-count'));
                if (isNaN(target)) return;

                var duration = 1100;
                var start = null;

                function step(ts) {
                    if (start === null) start = ts;
                    var progress = Math.min((ts - start) / duration, 1);
                    var eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress); // easeOutExpo
                    el.textContent = Math.round(target * eased).toLocaleString();
                    if (progress < 1) window.requestAnimationFrame(step);
                }

                window.requestAnimationFrame(step);
            });
        }, { threshold: 0.4 });

        nums.forEach(function (el) { io.observe(el); });
    }

    /* ------------------------------------------------------------------
     * Gallery category filters
     * ------------------------------------------------------------------ */
    function initFilters() {
        var buttons = $$('[data-filter]');
        if (!buttons.length) return;

        buttons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var category = btn.getAttribute('data-filter');

                buttons.forEach(function (b) { b.setAttribute('aria-pressed', String(b === btn)); });

                $$('[data-category]').forEach(function (item) {
                    item.hidden = !(category === 'all' || item.getAttribute('data-category') === category);
                });
            });
        });
    }

    /* ------------------------------------------------------------------
     * Accessible gallery lightbox (focus trap + keyboard navigation)
     * ------------------------------------------------------------------ */
    function initLightbox() {
        var lightbox = $('.v-lightbox');
        var grid = $('[data-gallery-grid]');
        if (!lightbox || !grid) return;

        var img = $('.v-lightbox__img', lightbox);
        var caption = $('.v-lightbox__caption', lightbox);
        var counter = $('.v-lightbox__count', lightbox);
        var btnClose = $('.v-lightbox__close', lightbox);
        var btnPrev = $('.v-lightbox__nav--prev', lightbox);
        var btnNext = $('.v-lightbox__nav--next', lightbox);
        var LB_FOCUSABLE = 'button:not([disabled]), [href], [tabindex]:not([tabindex="-1"])';
        var index = 0;

        function onExtraKey(e) {
            if (e.key === 'ArrowLeft') { step(-1); return true; }
            if (e.key === 'ArrowRight') { step(1); return true; }
            return false;
        }

        var dialog = makeFocusDialog(lightbox, lightbox, LB_FOCUSABLE, function () {
            return btnClose;
        }, onExtraKey);
        var close = dialog.close;

        function open(startIndex) {
            index = startIndex;
            dialog.open();
            render();
        }

        function items() {
            return $$('[data-category]', grid).filter(function (el) { return !el.hidden; });
        }

        function render() {
            var list = items();
            if (!list.length) return;
            var item = list[index];
            var source = $('img', item);
            var figcaption = $('.v-gallery__cap', item);

            img.setAttribute('src', source.getAttribute('src'));
            img.setAttribute('alt', source.getAttribute('alt') || '');
            caption.textContent = figcaption
                ? (figcaption.textContent || '').trim()
                : (source.getAttribute('alt') || '');
            counter.textContent = (index + 1) + ' / ' + list.length;
        }

        function step(delta) {
            var list = items();
            if (!list.length) return;
            index = (index + delta + list.length) % list.length;
            render();
        }

        $$('[data-gallery-item]', grid).forEach(function (trigger) {
            trigger.addEventListener('click', function () {
                var pos = items().indexOf(trigger);
                open(pos === -1 ? 0 : pos);
            });
        });

        if (btnClose) btnClose.addEventListener('click', close);
        if (btnPrev) btnPrev.addEventListener('click', function () { step(-1); });
        if (btnNext) btnNext.addEventListener('click', function () { step(1); });
    }

    /* ------------------------------------------------------------------
     * Boot
     * ------------------------------------------------------------------ */
    function init() {
        document.documentElement.classList.remove('no-js');
        initNav();
        initMobile();
        initSubmenus();
        initDropdowns();
        initReveals();
        initCounters();
        initFilters();
        initLightbox();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();