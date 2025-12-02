<x-guest-layout>
    <div class="form-title">
        <h2>Join Vipers Academy</h2>
        <p>Create your account to access exclusive training programs and player development opportunities</p>
    </div>

    <!-- Social Login Options -->
    <div class="social-login">
        <p>Register with</p>
        <a href="#" class="social-btn google" title="Register with Google">
            <i class="fab fa-google"></i>
        </a>
        <a href="#" class="social-btn facebook" title="Register with Facebook">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="#" class="social-btn twitter" title="Register with Twitter">
            <i class="fab fa-twitter"></i>
        </a>
    </div>

    <div style="text-align: center; margin: 1.5rem 0; position: relative;">
        <span style="background: rgba(255,255,255,0.9); padding: 0 1rem; color: #666; font-size: 0.9rem;">or register with email</span>
        <div style="position: absolute; top: 50%; left: 0; right: 0; height: 1px; background: #e1e5e9; z-index: -1;"></div>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Full Name" />
            @error('name')
                <div style="color: #e74c3c; font-size: 0.875rem; margin-top: 0.5rem; margin-left: 3rem;">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Email Address" />
            @error('email')
                <div style="color: #e74c3c; font-size: 0.875rem; margin-top: 0.5rem; margin-left: 3rem;">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Password" />
            @error('password')
                <div style="color: #e74c3c; font-size: 0.875rem; margin-top: 0.5rem; margin-left: 3rem;">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password" />
            @error('password_confirmation')
                <div style="color: #e74c3c; font-size: 0.875rem; margin-top: 0.5rem; margin-left: 3rem;">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Terms and Conditions -->
        <div style="margin-bottom: 1.5rem;">
            <label style="display: flex; align-items: center; font-size: 0.9rem; color: #666;">
                <input type="checkbox" required style="margin-right: 0.5rem; accent-color: #ff6b35;">
                I agree to the <a href="#" style="color: #ff6b35; text-decoration: none;">Terms & Conditions</a> and <a href="#" style="color: #ff6b35; text-decoration: none;">Privacy Policy</a>
            </label>
        </div>

        <button type="submit" class="btn-register">
            <i class="fas fa-user-plus" style="margin-right: 0.5rem;"></i>
            Create Account
        </button>

        <div class="login-link">
            <span style="color: #666; font-size: 0.9rem;">Already have an account? </span>
            <a href="{{ route('login') }}">Sign In</a>
        </div>
    </form>
</x-guest-layout>
