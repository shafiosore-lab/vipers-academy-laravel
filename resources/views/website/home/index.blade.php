@extends('layouts.academy')

@section('title', 'Mumias Vipers Football Academy - Youth Empowerment Through Sports, STEM & Education')

@section('meta_description', 'Mumias Vipers Football Academy: A community-based youth development organization combining football, STEM & education to empower underserved youth in Kenya.')

@section('content')
  <!-- Hero Section -->
<section class="hero-section home-hero-section" style="background-image: url('{{ asset('assets/img/home/teamb.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; background-color: #000;">
    <div class="hero-overlay" style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.70) 0%, rgba(0, 0, 0, 0.60) 70%);"></div>
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-10 col-xl-8">
                <div class="hero-content text-white" data-aos="fade-up">
                    <h1 class="hero-title fw-bold mb-2">
                        @php
                            $heroTitle = isset($pageContent['hero']) ? $pageContent['hero']->firstWhere('key', 'hero_title') : null;
                        @endphp
                        {{ $heroTitle?->value ?: 'Transforming Lives Through Football & Education' }}
                    </h1>
                    <p class="hero-subtitle mb-3">
                         @php
                             $heroSubtitle = isset($pageContent['hero']) ? $pageContent['hero']->firstWhere('key', 'hero_subtitle') : null;
                         @endphp
                         {{ $heroSubtitle?->value ?: 'Mumias Vipers Football Academy is a community-based organization that uses football as a platform for youth empowerment in digital skills, education, and life skills development across Kenya.' }}
                     </p>
                    <div class="hero-buttons d-flex flex-wrap gap-2 justify-content-center">
                         <a href="{{ route('programs') }}" class="btn btn-warning btn-lg px-4 py-3 fw-semibold shadow">
                             <i class="fas fa-compass me-2"></i>Explore Programs
                         </a>
                         <a href="{{ route('enrol') }}" class="btn btn-success btn-lg px-4 py-3 fw-semibold">
                             <i class="fas fa-user-plus me-2"></i>Join Training
                         </a>
                         <a href="{{ route('gamesuite') }}" class="btn btn-info btn-lg px-4 py-3 fw-semibold shadow d-flex align-items-center justify-content-center gamesuite-btn">
                             <span class="gamesuite-text">GameSuite</span>
                         </a>
                         <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-4 py-3 fw-semibold">
                             <i class="fas fa-headset me-2"></i>Contact Us
                         </a>
                     </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What We Do Section -->
  <section class="what-we-do py-4 bg-light">
      <div class="container">
          <div class="row align-items-center g-3">
              <div class="col-lg-6" data-aos="fade-right">
                  <div class="position-relative">
                      <img src="{{ asset('assets/img/home/WhatsApp%20Image%202026-01-21%20at%2012.47.01.jpeg') }}"
                           alt="Mumias Vipers Academy football training" class="img-fluid rounded-3 shadow-lg" loading="lazy">
                      <div class="play-button-overlay">
                          <i class="fas fa-play-circle fa-3x text-white"></i>
                      </div>
                  </div>
              </div>
               <div class="col-lg-6" data-aos="fade-left">
                   <h2 class="section-title fw-bold mb-3">
                       @php
                           $whatWeDoTitle = isset($pageContent['what_we_do']) ? $pageContent['what_we_do']->firstWhere('key', 'title') : null;
                       @endphp
                       @if($whatWeDoTitle?->value)
                           {{ $whatWeDoTitle->value }}
                       @else
                           {!! 'Empowering Youth Through <span class="text-primary">Sports, STEM & Education</span>' !!}
                       @endif
                   </h2>
                   <p class="section-text mb-3 text-muted">
                       @php
                           $whatWeDoDescription = isset($pageContent['what_we_do']) ? $pageContent['what_we_do']->firstWhere('key', 'description') : null;
                       @endphp
                       {{ $whatWeDoDescription?->value ?: 'Mumias Vipers Football Academy is a community-based organization that combines football training, STEM education (powered by E.N.G.I.N.E USA), academic support, and life skills development to empower underserved youth in Kenya. We provide structured programs that nurture talent, discipline, leadership, and academic excellence.' }}
                   </p>

                  <div class="row g-2 mb-3">
                      <div class="col-6">
                          <div class="feature-item d-flex align-items-center">
                              <i class="fas fa-futbol text-success me-2"></i>
                              <div>
                                  <div class="fw-semibold small">Sports for Development</div>
                                  <small class="text-muted">Football training & life skills</small>
                              </div>
                          </div>
                      </div>
                      <div class="col-6">
                          <div class="feature-item d-flex align-items-center">
                              <i class="fas fa-laptop text-primary me-2"></i>
                              <div>
                                  <div class="fw-semibold small">STEM & Digital Skills</div>
                                  <small class="text-muted">Powered by E.N.G.I.N.E USA</small>
                              </div>
                          </div>
                      </div>
                      <div class="col-6">
                          <div class="feature-item d-flex align-items-center">
                              <i class="fas fa-user-graduate text-warning me-2"></i>
                              <div>
                                  <div class="fw-semibold small">Academic Support</div>
                                  <small class="text-muted">Tutoring & scholarship guidance</small>
                              </div>
                          </div>
                      </div>
                      <div class="col-6">
                          <div class="feature-item d-flex align-items-center">
                              <i class="fas fa-hands-helping text-info me-2"></i>
                              <div>
                                  <div class="fw-semibold small">Youth Mentorship</div>
                                  <small class="text-muted">Life skills & mental wellbeing</small>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="text-center mt-3">
                      <a href="{{ route('enrol') }}" class="btn btn-success px-4 py-2 fw-semibold shadow">
                          <i class="fas fa-user-plus me-2"></i>Join Our Program
                      </a>
                  </div>
              </div>
          </div>
      </div>
  </section>

<!-- Success Stories Section -->
  <section class="success-stories py-4 bg-white">
      <div class="container">
           <div class="text-center mb-3" data-aos="fade-up">
               <h2 class="section-title fw-bold mb-2">
                   @php
                       $storiesTitle = isset($pageContent['stories']) ? $pageContent['stories']->firstWhere('key', 'title') : null;
                   @endphp
                   @if($storiesTitle?->value)
                       {{ $storiesTitle->value }}
                   @else
                       {!! 'Success <span class="text-success">Stories</span>' !!}
                   @endif
               </h2>
               <p class="section-subtitle text-muted">
                   @php
                       $storiesSubtitle = isset($pageContent['stories']) ? $pageContent['stories']->firstWhere('key', 'subtitle') : null;
                   @endphp
                   {{ $storiesSubtitle?->value ?: 'Real lives transformed through football-powered youth development' }}
               </p>
           </div>

          <div class="row g-3">
              <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                  <div class="story-card card border-0 shadow-sm h-100">
                      <div class="card-body p-3 text-center">
                          <img src="{{ asset('assets/img/home/WhatsApp%20Image%202026-01-21%20at%2012.46.59.jpeg') }}"
                               alt="Scholarship recipient" class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;" loading="lazy">
                          <h5 class="card-title fw-bold mb-1">Education Through Football</h5>
                          <p class="card-text text-muted mb-2 small">
                              "Thanks to Vipers Academy's community programs, I received a full sports scholarship to secondary school. Football opened doors to education I never thought possible. The mentorship and discipline I gained here changed my life."
                          </p>
                          <div class="text-warning mb-1">
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                          </div>
                          <small class="text-muted">Scholarship Recipient, Class of 2024</small>
                      </div>
                  </div>
              </div>

              <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                  <div class="story-card card border-0 shadow-sm h-100">
                      <div class="card-body p-3 text-center">
                          <img src="{{ asset('assets/img/home/under%2013.jpeg') }}"
                               alt="Community member" class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;" loading="lazy">
                          <h5 class="card-title fw-bold mb-1">Community Transformation</h5>
                          <p class="card-text text-muted mb-2 small">
                              "The academy has brought our community together. My son learned discipline, digital skills, and now mentors younger players. The programs build character and create opportunities for our youth."
                          </p>
                          <div class="text-warning mb-1">
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                          </div>
                          <small class="text-muted">Parent & Community Member</small>
                      </div>
                  </div>
              </div>

              <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                  <div class="story-card card border-0 shadow-sm h-100">
                      <div class="card-body p-3 text-center">
                           <img src="{{ asset('assets/img/home/ddd.png') }}"
                                alt="Young player" class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;" loading="lazy">
                          <h5 class="card-title fw-bold mb-1">Life Skills Development</h5>
                          <p class="card-text text-muted mb-2 small">
                              "Vipers taught me more than football. I learned responsibility, teamwork, digital literacy, and how to balance sports with studies. These skills will guide me through life."
                          </p>
                          <div class="text-warning mb-1">
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                          </div>
                          <small class="text-muted">Brian Onyango, Age 15</small>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </section>

<!-- Programs Section -->
<section class="programs-section py-4 bg-light">
    <div class="container">
        <div class="text-center mb-3" data-aos="fade-up">
            <h2 class="section-title fw-bold mb-2">
                @php
                    $programsTitle = isset($pageContent['programs']) ? $pageContent['programs']->firstWhere('key', 'title') : null;
                @endphp
                @if($programsTitle?->value)
                    {{ $programsTitle->value }}
                @else
                    {!! 'Football <span class="text-success">|</span> Academics <span class="text-warning">|</span> Technology' !!}
                @endif
            </h2>
            <p class="section-subtitle">
                @php
                    $programsSubtitle = isset($pageContent['programs']) ? $pageContent['programs']->firstWhere('key', 'subtitle') : null;
                @endphp
                {{ $programsSubtitle?->value ?: 'Choose from our comprehensive range of youth development programs' }}
            </p>
        </div>

        <div class="row g-4">
            <!-- Football Training -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="program-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center d-flex flex-column">
                        <div class="program-icon mb-3">
                            <i class="fas fa-futbol fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-2">Football Training</h5>
                        <p class="text-muted mb-3 fw-medium" style="font-size: 0.95rem;">Professional Skills Development</p>
                        <ul class="program-features list-unstyled mb-3 text-start px-2">
                            <li class="mb-2 d-flex align-items-start"><i class="fas fa-calendar-week feature-icon mt-1 me-2 text-success"></i><span>Weekend training sessions</span></li>
                            <li class="mb-2 d-flex align-items-start"><i class="fas fa-chalkboard-teacher feature-icon mt-1 me-2 text-success"></i><span>Theory & tactical classes</span></li>
                            <li class="mb-2 d-flex align-items-start"><i class="fas fa-users feature-icon mt-1 me-2 text-success"></i><span>Age-appropriate groups</span></li>
                            <li class="mb-2 d-flex align-items-start"><i class="fas fa-trophy feature-icon mt-1 me-2 text-success"></i><span>Tournament participation</span></li>
                        </ul>
                        <div class="d-flex flex-wrap gap-2 justify-content-center mt-auto">
                             <span class="badge bg-success px-3 py-2">Weekend Programs</span>
                             <span class="badge bg-success bg-opacity-25 text-success px-3 py-2">Community Program</span>
                         </div>
                    </div>
                </div>
            </div>

            <!-- Academic Mentorship -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="program-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center d-flex flex-column">
                        <div class="program-icon mb-3">
                            <i class="fas fa-graduation-cap fa-3x text-warning"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-2">Academic Mentorship</h5>
                        <p class="text-muted mb-3 fw-medium" style="font-size: 0.95rem;">CBC-Aligned Support</p>
                        <ul class="program-features list-unstyled mb-3 text-start px-2">
                            <li class="mb-2 d-flex align-items-start"><i class="fas fa-book-open feature-icon mt-1 me-2 text-warning"></i><span>Study discipline coaching</span></li>
                            <li class="mb-2 d-flex align-items-start"><i class="fas fa-clipboard-list feature-icon mt-1 me-2 text-warning"></i><span>CBC homework support</span></li>
                            <li class="mb-2 d-flex align-items-start"><i class="fas fa-hands-helping feature-icon mt-1 me-2 text-warning"></i><span>Life-skills mentorship</span></li>
                            <li class="mb-2 d-flex align-items-start"><i class="fas fa-chart-line feature-icon mt-1 me-2 text-warning"></i><span>Behavior tracking system</span></li>
                        </ul>
                        <div class="d-flex flex-wrap gap-2 justify-content-center mt-auto">
                             <span class="badge bg-warning text-dark px-3 py-2">Study Support</span>
                             <span class="badge bg-warning bg-opacity-25 text-warning px-3 py-2">Youth Program</span>
                         </div>
                    </div>
                </div>
            </div>

            <!-- Digital Skills -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="program-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center d-flex flex-column">
                        <div class="program-icon mb-3">
                            <i class="fas fa-laptop fa-3x text-info"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-2">Digital Skills</h5>
                        <p class="text-muted mb-3 fw-medium" style="font-size: 0.95rem;">Technology Integration</p>
                        <ul class="program-features list-unstyled mb-3 text-start px-2">
                            <li class="mb-2 d-flex align-items-start"><i class="fas fa-desktop feature-icon mt-1 me-2 text-info"></i><span>Computer basics training</span></li>
                            <li class="mb-2 d-flex align-items-start"><i class="fas fa-code feature-icon mt-1 me-2 text-info"></i><span>Introduction to coding</span></li>
                            <li class="mb-2 d-flex align-items-start"><i class="fas fa-shield-alt feature-icon mt-1 me-2 text-info"></i><span>Digital safety education</span></li>
                            <li class="mb-2 d-flex align-items-start"><i class="fas fa-lightbulb feature-icon mt-1 me-2 text-info"></i><span>Creative tech projects</span></li>
                        </ul>
                        <div class="d-flex flex-wrap gap-2 justify-content-center mt-auto">
                             <span class="badge bg-info px-3 py-2">Computer Lab Access</span>
                             <span class="badge bg-info bg-opacity-25 text-info px-3 py-2">Digital Program</span>
                         </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-3" data-aos="fade-up">
            <a href="{{ route('enrol') }}" class="btn btn-primary px-4 py-2 me-2 mb-2">
                Enroll Your Child
            </a>
            <a href="{{ route('programs') }}" class="btn btn-outline-primary px-4 py-2 mb-2">
                View Program Details
            </a>
        </div>
    </div>
</section>

    <!-- Impact/Stats Section -->
     <section class="impact-section py-4 bg-light">
         <div class="container">
             <div class="text-center mb-4" data-aos="fade-up">
                 <h2 class="section-title fw-bold mb-2">
                     Our Community Impact
                 </h2>
                 <p class="section-subtitle text-muted">Measurable impact through youth development in Kenya</p>
             </div>

            <div class="row g-3 text-center" id="impact-stats">
                 <div class="col-12 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                     <div class="stat-card h-100 p-3 bg-white rounded-3 shadow-sm transition-all">
                         <div class="stat-icon mb-2">
                             <i class="fas fa-graduation-cap fa-2x text-primary"></i>
                         </div>
                         <div class="stat-number fw-bold" data-target="20" data-suffix="+">0</div>
                         <p class="stat-label mb-0 text-muted">Scholarship Pathways Created</p>
                     </div>
                 </div>
                 <div class="col-12 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                     <div class="stat-card h-100 p-3 bg-white rounded-3 shadow-sm transition-all">
                         <div class="stat-icon mb-2">
                             <i class="fas fa-users fa-2x text-secondary"></i>
                         </div>
                         <div class="stat-number fw-bold" data-target="500" data-suffix="+">0</div>
                         <p class="stat-label mb-0 text-muted">Youth Empowered</p>
                     </div>
                 </div>
                 <div class="col-12 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                     <div class="stat-card h-100 p-3 bg-white rounded-3 shadow-sm transition-all">
                         <div class="stat-icon mb-2">
                             <i class="fas fa-venus-mars fa-2x text-warning"></i>
                         </div>
                         <div class="stat-number fw-bold" data-target="50" data-suffix="+">0</div>
                         <p class="stat-label mb-0 text-muted">Girls Participating</p>
                     </div>
                 </div>
                 <div class="col-12 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                     <div class="stat-card h-100 p-3 bg-white rounded-3 shadow-sm transition-all">
                         <div class="stat-icon mb-2">
                             <i class="fas fa-hands-helping fa-2x text-info"></i>
                         </div>
                         <div class="stat-number fw-bold" data-target="50" data-suffix="+">0</div>
                         <p class="stat-label mb-0 text-muted">Community Mentors & Volunteers</p>
                     </div>
                 </div>
             </div>

            <div class="text-center mt-4" data-aos="fade-up">
                 <a href="{{ route('donate') }}" class="btn btn-primary btn-lg px-4 py-2 fw-semibold shadow">
                     <i class="fas fa-heart me-2"></i>Support Our Youth Programs
                 </a>
             </div>
        </div>
    </section>

<!-- Partners Section -->
@php
    $fallbackPartners = [
        (object)[
            'name' => 'E.N.G.I.N.E USA',
            'logo_src' => asset('assets/img/partners/engine-logo.png'),
            'website' => 'https://engineorg.com/',
        ],
        (object)[
            'name' => 'Medsply Hospitals',
            'logo_src' => asset('assets/img/partners/medsply-logo.png'),
            'website' => 'http://medsplyhospitals.com/',
        ],
    ];
    $displayPartners = (isset($partners) && $partners->count() > 0) ? $partners : collect($fallbackPartners);
@endphp
<section class="partners-section py-4 bg-white">
    <div class="container">
        <div class="text-center mb-3" data-aos="fade-up">
            <h2 class="section-title fw-bold mb-2">
                Our <span class="text-success">Partners</span> & Collaborators
            </h2>
            <p class="section-subtitle text-muted">Proud to work with leading organizations committed to youth development</p>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-6 col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="stats-card card border-0 bg-success bg-opacity-10 h-100">
                    <div class="card-body text-center py-2">
                        <div class="stats-icon mb-1">
                            <i class="fas fa-building fa-2x text-success"></i>
                        </div>
                        <h3 class="fw-bold text-success mb-0">{{ $displayPartners->count() }}+</h3>
                        <p class="text-muted mb-0 small">Partner Organizations</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="stats-card card border-0 bg-warning bg-opacity-10 h-100">
                    <div class="card-body text-center py-2">
                        <div class="stats-icon mb-1">
                            <i class="fas fa-hand-holding-heart fa-2x text-warning"></i>
                        </div>
                        <h3 class="fw-bold text-warning mb-0">KSh {{ isset($partners) && $partners->count() > 0 ? number_format($partners->sum(function($p) {
                            $details = is_array($p->partner_details) ? $p->partner_details : json_decode($p->partner_details, true);
                            return $details['annual_contribution'] ?? 0;
                        })) : '0' }}</h3>
                        <p class="text-muted mb-0 small">Annual Support Value</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="stats-card card border-0 bg-info bg-opacity-10 h-100">
                    <div class="card-body text-center py-2">
                        <div class="stats-icon mb-1">
                            <i class="fas fa-calendar-check fa-2x text-info"></i>
                        </div>
                        <h3 class="fw-bold text-info mb-0">{{ $displayPartners->count() }}+</h3>
                        <p class="text-muted mb-0 small">Active Collaborations</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="partners-carousel-wrapper mb-3" data-aos="fade-up">
            <div class="partners-carousel-track" id="partnersTrack">
                @foreach($displayPartners as $partner)
                <div class="partner-logo-item">
                    <a href="{{ $partner->website ?? '#' }}" target="_blank" rel="noopener noreferrer" class="partner-logo-link">
                        <div class="partner-logo-card">
                            <div class="partner-logo-wrapper">
                                @if(isset($partner->logo_src))
                                <img src="{{ $partner->logo_src }}" alt="{{ $partner->name }}" class="partner-logo-img" loading="lazy">
                                @elseif(isset($partner->logo) && $partner->logo)
                                <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->name }}" class="partner-logo-img" loading="lazy">
                                @else
                                <div class="partner-initials">{{ strtoupper(substr($partner->name, 0, 2)) }}</div>
                                @endif
                            </div>
                            <h6 class="partner-name mb-0">{{ Str::limit($partner->name, 15) }}</h6>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        <div class="text-center mt-4" data-aos="fade-up">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #001f3f 0%, #003366 100%);">
                <div class="card-body py-3 px-4">
                    <h4 class="fw-bold text-white mb-1">Become a Partner</h4>
                    <p class="text-white-50 mb-2">Join our network and help shape the future of Kenyan youth</p>
                    <a href="{{ route('contact') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-handshake me-2"></i>Partner With Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* ========================================
   HOME PAGE SPECIFIC STYLES
   ======================================== */

.hero-section {
    position: relative;
    min-height: 70vh;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    background-size: cover;
    background-position: center;
    overflow: hidden;
    padding-top: 2rem; /* Compact spacing from top */
    border-bottom-left-radius: 12px;
    border-bottom-right-radius: 12px;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    border-bottom-left-radius: 12px;
    border-bottom-right-radius: 12px;
}

    .hero-content {
        position: relative;
        z-index: 2;
        color: var(--white);
        padding-top: 1rem; /* Additional spacing from very top */
    }

    /* Responsive hero content spacing */
    @media (min-width: 992px) {
        .hero-content {
            padding-top: 2rem; /* Desktop spacing */
        }
    }

    @media (min-width: 576px) and (max-width: 991px) {
        .hero-content {
            padding-top: 1.5rem; /* Tablet spacing */
        }
    }

    @media (max-width: 575px) {
        .hero-content {
            padding-top: 1rem; /* Mobile spacing */
        }
    }

/* Program Cards Typography */
.program-card .card-title {
    font-size: 1.2rem;
    letter-spacing: -0.01em;
}

.program-features {
    flex-grow: 1;
}

.program-features li {
    font-size: 0.925rem;
    line-height: 1.5;
    color: #444;
}

.feature-icon {
    width: 18px;
    text-align: center;
    flex-shrink: 0;
}

.program-card .badge {
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.02em;
}

.program-card .card-body {
    transition: all 0.3s ease;
}

.program-card:hover .card-body {
    transform: translateY(-2px);
}

/* Responsive: Mobile program features */
@media (max-width: 991px) {
    .program-card .card-title {
        font-size: 1.1rem;
    }

    .program-features li {
        font-size: 0.875rem;
    }

    .program-card .badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.65rem !important;
    }
}

@media (max-width: 576px) {
    .program-card .card-title {
        font-size: 1rem;
    }

    .program-features li {
        font-size: 0.85rem;
    }
}

@media (min-width: 992px) {
    .hero-section {
        min-height: 75vh;
        max-height: 800px;
        padding-top: 1.5rem; /* Reduced from 80px for compact view */
        align-items: flex-start;
        justify-content: center;
    }
    .hero-title { font-size: 2.75rem; margin-bottom: 1rem; }
    .hero-subtitle { font-size: 1.05rem; margin-bottom: 2rem; }
    .hero-buttons .btn { font-size: 0.95rem; padding: 0.65rem 1.25rem; }
}

@media (min-width: 576px) and (max-width: 991px) {
    .hero-section {
        min-height: 55vh;
        padding-top: 1.25rem; /* Reduced from 80px for compact view */
        align-items: flex-start;
        justify-content: center;
    }
    .hero-title { font-size: 2rem; margin-bottom: 1rem; }
    .hero-subtitle { margin-bottom: 2rem; }
}

@media (max-width: 575px) {
    .hero-section {
        min-height: 50vh;
        padding-top: 1rem; /* Reduced from 70px for compact mobile view */
        padding-bottom: 1.5rem;
        align-items: flex-start;
        justify-content: center;
    }
    .hero-title { font-size: 1.5rem; margin-bottom: 0.75rem; }
    .hero-subtitle { font-size: 0.85rem; margin-bottom: 1.5rem; }
    .hero-buttons {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 8px !important;
        width: 100%;
    }
    .hero-buttons .btn {
        width: 100% !important;
        text-align: center;
        padding: 10px 8px !important;
        font-size: 0.8rem !important;
        min-height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        border-radius: 8px;
    }
    .hero-buttons .btn i { font-size: 0.8rem; margin-right: 4px; }
}

@media (min-width: 1200px) {
    .partners-section { position: relative; overflow: hidden; }
    .partners-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #ea1c4d, #fbc761, #ea1c4d);
    }
}

.partners-carousel-wrapper {
    overflow: hidden;
    position: relative;
    width: 100%;
    padding: 0.75rem 0;
    background: linear-gradient(90deg, rgba(255,255,255,1) 0%, rgba(248,249,250,1) 50%, rgba(255,255,255,1) 100%);
    border-top: 1px solid rgba(0,0,0,0.05);
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.partners-carousel-track {
    display: flex;
    gap: 1.5rem;
    width: max-content;
    animation: scroll-left 30s linear infinite;
    padding-left: 0;
    padding-right: 0;
}

.partners-carousel-track:hover { animation-play-state: paused; }

@keyframes scroll-left {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

.partner-logo-link {
    text-decoration: none;
    color: inherit;
    display: block;
    height: 100%;
}

.partner-logo-link:hover { text-decoration: none; color: inherit; }

.partner-logo-item { flex-shrink: 0; width: 160px; }

.partner-logo-card {
    background: #fff;
    border-radius: 10px;
    padding: 1rem 0.75rem;
    text-align: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    border: 1px solid rgba(0,0,0,0.04);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.partner-logo-card:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,0.12); }

.partner-logo-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 50px;
    width: 100%;
    margin-bottom: 0.5rem;
}

.partner-logo-img {
    max-width: 100%;
    max-height: 45px;
    width: auto;
    height: auto;
    object-fit: contain;
    filter: grayscale(20%);
    transition: filter 0.3s ease;
}

.partner-logo-card:hover .partner-logo-img { filter: grayscale(0%); }

.partner-initials {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
}

.partner-name { font-size: 0.8rem; font-weight: 600; color: #333; line-height: 1.3; }

@media (max-width: 991px) {
    .partners-carousel-track { gap: 1.25rem; }
    .partner-logo-item { width: 140px; }
    .partner-logo-card { padding: 0.875rem 0.5rem; }
    .partner-logo-wrapper { height: 45px; }
    .partner-logo-img { max-height: 35px; }
}

@media (max-width: 576px) {
    .partners-carousel-track { gap: 1rem; animation-duration: 25s; }
    .partner-logo-item { width: 120px; }
    .partner-logo-card { padding: 0.75rem 0.375rem; border-radius: 8px; }
    .partner-logo-wrapper { height: 40px; margin-bottom: 0.375rem; }
    .partner-logo-img { max-height: 30px; }
    .partner-initials { width: 35px; height: 35px; font-size: 0.85rem; }
    .partner-name { font-size: 0.7rem; }
}

@media (max-width: 400px) {
    .partners-carousel-track { gap: 0.75rem; }
    .partner-logo-item { width: 100px; }
    .partner-logo-card { padding: 0.5rem 0.25rem; }
    .partner-logo-wrapper { height: 35px; }
    .partner-logo-img { max-height: 25px; }
    .partner-initials { width: 30px; height: 30px; font-size: 0.75rem; }
    .partner-name { font-size: 0.65rem; }
}

/* Stats/Impact Cards Mobile Styles */
@media (max-width: 991px) {
    .stat-card { min-height: auto !important; padding: 0.75rem !important; }
    .stat-card .stat-icon { margin-bottom: 0.25rem !important; }
    .stat-card .stat-icon i { font-size: 1.25rem !important; }
    .stat-card .stat-number { font-size: 1.25rem !important; margin-bottom: 0.15rem !important; }
    .stat-card .stat-label { font-size: 0.7rem !important; line-height: 1.2; }
}

@media (max-width: 576px) {
    #impact-stats {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 0.5rem !important;
    }
    #impact-stats > div { grid-column: span 1 !important; }
    #impact-stats .stat-card {
        padding: 0.5rem !important;
        min-height: auto !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        align-items: center !important;
        text-align: center !important;
    }
    #impact-stats .stat-card .stat-icon { margin-bottom: 0.2rem !important; }
    #impact-stats .stat-card .stat-icon i { font-size: 1.1rem !important; }
    #impact-stats .stat-card .stat-number { font-size: 1.1rem !important; margin-bottom: 0.1rem !important; line-height: 1.1; }
    #impact-stats .stat-card .stat-label { font-size: 0.6rem !important; line-height: 1.1; margin-bottom: 0 !important; }
}

/* GameSuite Button */
.gamesuite-btn {
    background: linear-gradient(135deg, #17a2b8, #0dcaf0) !important;
    border: none !important;
    position: relative;
    overflow: hidden;
}

.gamesuite-text {
    font-size: 1.15rem;
    font-weight: 800;
    color: #fff;
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    letter-spacing: 0.5px;
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Intersection Observer for count-up animation
    const observerOptions = { root: null, rootMargin: '0px', threshold: 0.3 };
    const countUpObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statCards = entry.target.querySelectorAll('.stat-card');
                statCards.forEach((card, index) => {
                    const numberEl = card.querySelector('.stat-number');
                    if (numberEl) {
                        const target = parseInt(numberEl.dataset.target);
                        const suffix = numberEl.dataset.suffix || '';
                        setTimeout(() => { animateCounter(numberEl, target, suffix); }, index * 150);
                    }
                });
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const impactStats = document.getElementById('impact-stats');
    if (impactStats) countUpObserver.observe(impactStats);

    function animateCounter(element, target, suffix) {
        const duration = 2000;
        const startTime = performance.now();
        function easeOutQuart(t) { return 1 - Math.pow(1 - t, 4); }
        function updateCounter(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easedProgress = easeOutQuart(progress);
            const currentValue = Math.round(0 + (target - 0) * easedProgress);
            element.textContent = currentValue + suffix;
            element.classList.add('counting');
            if (progress < 1) requestAnimationFrame(updateCounter);
            else { element.textContent = target + suffix; element.classList.remove('counting'); }
        }
        requestAnimationFrame(updateCounter);
    }

    // Seamless partner carousel
    const track = document.getElementById('partnersTrack');
    if (track) {
        const items = track.querySelectorAll('.partner-logo-item');
        if (items.length > 0) {
            const itemWidth = items[0].offsetWidth + 24;
            const totalWidth = itemWidth * items.length;
            const viewportWidth = track.parentElement.offsetWidth;
            const copiesNeeded = Math.max(2, Math.ceil((viewportWidth * 2) / totalWidth));
            for (let c = 0; c < copiesNeeded; c++) {
                items.forEach(item => { track.appendChild(item.cloneNode(true)); });
            }
        }
    }
});
</script>
@endpush
