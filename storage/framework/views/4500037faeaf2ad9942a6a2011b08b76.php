<?php $__env->startSection('page_title', 'Contact Us'); ?>

<?php $__env->startSection('body'); ?>

<div class="ml-container">
    <nav class="ml-breadcrumb"><a href="<?php echo e(url('/')); ?>">Home</a> / <span class="active">Contact Us</span></nav>
</div>

<section class="pb-4">
    <div class="ml-container">
        <div class="ml-hero">
            <span class="ml-eyebrow"><i class="fa-solid fa-tower-broadcast"></i> Get in Touch</span>
            <h1 class="display-6">Contact MarketLink</h1>
            <p class="text-muted mt-2" style="max-width:600px;">Have a question about MarketLink? Get in touch with our team and learn more about the platform connecting local farmers, markets, and customers through honest, in-person pickups.</p>
            <div class="d-flex flex-wrap gap-3 mt-3 small text-muted">
                <span class="ml-badge ml-badge-mint"><i class="fa-solid fa-tower-broadcast"></i> Static Team Contact Desk</span>
                <span class="ml-badge ml-badge-mint"><i class="fa-solid fa-location-dot"></i> Project Location &amp; Map Discovery</span>
                <span class="ml-badge ml-badge-mint"><i class="fa-solid fa-ban"></i> No Online Payment or Delivery Inquiries</span>
            </div>
        </div>
    </div>
</section>

<section class="ml-section pt-2">
    <div class="ml-container">
        <div class="ml-heading-block">
            <span class="ml-eyebrow"><i class="fa-solid fa-address-book"></i> Contact Our Team</span>
        </div>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="ml-card h-100">
                    <div class="ml-step__icon"><i class="fa-solid fa-people-group"></i></div>
                    <strong class="d-block mt-2">Team Central Desk</strong>
                    <span class="small text-success">Project Team &amp; Platform Inquiries</span>
                    <ul class="list-unstyled small text-muted mt-2 mb-0">
                        <li class="mb-1"><i class="fa-solid fa-envelope"></i> team@marketlink-project.example</li>
                        <li class="mb-1"><i class="fa-solid fa-phone"></i> +92 300 0000000</li>
                        <li><i class="fa-regular fa-clock"></i> Mon &ndash; Fri 09:00 AM &ndash; 05:00 PM (PKT)</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ml-card h-100">
                    <div class="ml-step__icon"><i class="fa-solid fa-tractor"></i></div>
                    <strong class="d-block mt-2">Stallholder Desk</strong>
                    <span class="small text-success">Farmer &amp; Pavilion Onboarding</span>
                    <ul class="list-unstyled small text-muted mt-2 mb-0">
                        <li class="mb-1"><i class="fa-solid fa-envelope"></i> farmers@marketlink-project.example</li>
                        <li><i class="fa-solid fa-seedling"></i> For local growers registering weekly stall slots &amp; catalog profiles.</li>
                    </ul>
                    <span class="ml-badge ml-badge-mint mt-2">Farmer verification turnaround: 24h</span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ml-card h-100">
                    <span class="ml-badge ml-badge-mint">Collaborative Architecture</span>
                    <strong class="d-block mt-2">MarketLink Project Desk</strong>
                    <p class="small text-muted mt-2">Working together to build a dependable, full-stack open web platform connecting community farmers with neighborhood shoppers.</p>
                    <div class="row g-2 text-center">
                        <div class="col-4"><div class="ml-badge ml-badge-mint w-100">4 Core Devs</div></div>
                        <div class="col-4"><div class="ml-badge ml-badge-mint w-100">100% Open SRS</div></div>
                        <div class="col-4"><div class="ml-badge ml-badge-mint w-100">PKT Hub</div></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ml-alert ml-alert-warning mt-4">
            <i class="fa-solid fa-circle-info"></i>
            <div>
                <strong class="d-block">Direct Pickup Notice</strong>
                <span class="small">MarketLink is built for local weekend market pickup and direct farm-to-table pre-orders. All payments are completed strictly in person at your selected market stall. We do not provide courier shipping or online transaction processing.</span>
            </div>
        </div>
    </div>
</section>

<section class="ml-section ml-section--muted">
    <div class="ml-container">
        <div class="ml-card">
            <h2>Send Us a Message</h2>
            <p class="text-muted">Have a question or need information about the MarketLink project? Send an inquiry directly to our core coordination team.</p>
            <form class="row g-3 mt-2" method="POST" action="<?php echo e(url('/contact')); ?>">
                <?php echo csrf_field(); ?>
                <div class="col-md-6">
                    <label class="ml-form-label">Full Name *</label>
                    <input type="text" name="full_name" value="<?php echo e(old('full_name')); ?>" class="form-control <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g., Tariq Mahmood" required>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label class="ml-form-label">Email Address *</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g., tariq@example.com" required>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="col-12">
                    <label class="ml-form-label">Inquiry Subject *</label>
                    <select name="subject" class="form-select <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="" disabled <?php echo e(old('subject') ? '' : 'selected'); ?>>Select an inquiry area...</option>
                        <option value="Market &amp; Pickup Questions" <?php echo e(old('subject') == 'Market &amp; Pickup Questions' ? 'selected' : ''); ?>>Market &amp; Pickup Questions</option>
                        <option value="Farmer Onboarding" <?php echo e(old('subject') == 'Farmer Onboarding' ? 'selected' : ''); ?>>Farmer Onboarding</option>
                        <option value="Platform Feedback" <?php echo e(old('subject') == 'Platform Feedback' ? 'selected' : ''); ?>>Platform Feedback</option>
                        <option value="Other" <?php echo e(old('subject') == 'Other' ? 'selected' : ''); ?>>Other</option>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="col-12">
                    <label class="ml-form-label">Message *</label>
                    <textarea name="message" class="form-control <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" placeholder="Write your message or inquiry here..." required><?php echo e(old('message')); ?></textarea>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span class="small text-muted">Our team reviews academic &amp; community inquiries during regular working hours (Mon-Fri).</span>
                    <button type="submit" class="btn ml-btn-primary">Send Message <i class="fa-solid fa-paper-plane"></i></button>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="ml-section">
    <div class="ml-container">
        <div class="ml-heading-block">
            <span class="ml-eyebrow">Engineering &amp; Research</span>
            <h2>Meet the Team</h2>
            <p>Core contributors developing and maintaining the MarketLink open platform specification.</p>
        </div>
        <div class="row g-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                ['name' => 'Asim Khan', 'role' => 'Full-Stack &amp; Integration', 'desc' => 'Specializing in end-to-end architecture, API contracts, and stallholder workflow routes.', 'email' => 'asim.khan@example.com'],
                ['name' => 'Sarah Ahmed', 'role' => 'Frontend &amp; UI/UX Experience', 'desc' => 'Directing responsive agrarian layouts, intuitive market catalog filters, and accessibility standards.', 'email' => 'sarah.ahmed@example.com'],
                ['name' => 'Bilal Hassan', 'role' => 'Database &amp; MySQL Core', 'desc' => 'Engineering normalized relational schemas for produce inventory, weekly slots, and harvest audits.', 'email' => 'bilal.hassan@example.com'],
                ['name' => 'Fatima Noor', 'role' => 'QA &amp; SRS Verification', 'desc' => 'Executing rigorous functional compliance matrices and direct in-person pre-order pickup validations.', 'email' => 'fatima.noor@example.com'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-6 col-lg-3">
                <div class="ml-card text-center h-100">
                    <i class="fa-solid fa-circle-user fs-1 text-success"></i>
                    <strong class="d-block mt-2"><?php echo e($member['name']); ?></strong>
                    <span class="small text-success d-block"><?php echo $member['role']; ?></span>
                    <p class="small text-muted mt-2"><?php echo $member['desc']; ?></p>
                    <span class="small text-muted"><i class="fa-solid fa-at"></i> <?php echo e($member['email']); ?></span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>

<section class="ml-section ml-section--muted">
    <div class="ml-container">
        <div class="row g-4">
            <div class="col-lg-5">
                <span class="ml-eyebrow">Hub Coordinates</span>
                <h2>Our Location</h2>
                <p class="text-muted mt-2">Find the MarketLink project team and administrative coordination desk on the regional map.</p>
                <div class="ml-card mt-3">
                    <strong><i class="fa-solid fa-building text-success"></i> MarketLink Project Hub</strong>
                    <span class="small text-muted d-block">Department of Computer Science</span>
                    <span class="small text-muted d-block mt-2"><i class="fa-solid fa-location-dot"></i> Innovation Pavilion, Main University Road, Gulshan-e-Iqbal Campus, Karachi, Sindh, Pakistan</span>
                    <span class="small text-muted d-block mt-2"><i class="fa-regular fa-calendar"></i> Monday through Friday (Excluding Market Weekends)</span>
                    <span class="small text-muted d-block"><i class="fa-regular fa-clock"></i> 09:00 AM &ndash; 05:00 PM PKT</span>
                    <a href="#" class="btn ml-btn-secondary ml-btn-block mt-3">Open in Google Maps / OSM</a>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ml-map">
                    <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=1200&q=60" alt="Karachi map">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ml-section">
    <div class="ml-container">
        <div class="ml-heading-block mx-auto text-center">
            <span class="ml-eyebrow">Assistance</span>
            <h2>Quick Questions</h2>
            <p class="mx-auto">Common queries regarding MarketLink's open platform and field-to-stall ethos.</p>
        </div>
        <div class="ml-accordion mx-auto" style="max-width:760px;">
            <div class="ml-accordion-item is-open">
                <button class="ml-accordion-header" type="button">What is MarketLink? <i class="fa-solid fa-chevron-down"></i></button>
                <div class="ml-accordion-body">MarketLink is an open web platform engineered to connect local farmers-market growers with neighborhood customers, enabling weekly harvest browsing, pre-orders, and convenient in-person stall pickups without intermediary middleman marks.</div>
            </div>
            <div class="ml-accordion-item">
                <button class="ml-accordion-header" type="button">Who can use MarketLink? <i class="fa-solid fa-chevron-down"></i></button>
                <div class="ml-accordion-body">Any customer seeking fresh local produce and any verified local farmer selling at a participating community market can use MarketLink.</div>
            </div>
            <div class="ml-accordion-item">
                <button class="ml-accordion-header" type="button">How can I contact the development team? <i class="fa-solid fa-chevron-down"></i></button>
                <div class="ml-accordion-body">Reach the core coordination team using the contact form above or email team@marketlink-project.example during regular working hours.</div>
            </div>
        </div>
    </div>
</section>

<section class="ml-section ml-section--muted">
    <div class="ml-container">
        <div class="ml-heading-block">
            <h2>Explore MarketLink</h2>
            <p>Navigate our active marketplace services directly.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="ml-card">
                    <i class="fa-solid fa-store text-success fs-4"></i>
                    <strong class="d-block mt-2">Browse Markets</strong>
                    <p class="small text-muted">Discover weekend farmers markets, scheduled locations, and collection cutoff times across your district.</p>
                    <a href="<?php echo e(url('/markets')); ?>" class="ml-btn-link small">Explore Markets <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ml-card">
                    <i class="fa-solid fa-carrot text-success fs-4"></i>
                    <strong class="d-block mt-2">Browse Products</strong>
                    <p class="small text-muted">View live weekly harvest stock, seasonal root vegetables, and fresh greens listed by verified growers.</p>
                    <a href="<?php echo e(url('/products')); ?>" class="ml-btn-link small">View Products <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ml-card">
                    <i class="fa-solid fa-tractor text-success fs-4"></i>
                    <strong class="d-block mt-2">Meet Farmers</strong>
                    <p class="small text-muted">Explore dedicated farmer profiles, sustainable crop rotations, and operating stalls in your locality.</p>
                    <a href="<?php echo e(url('/farmers')); ?>" class="ml-btn-link small">View Farmers <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ml-section">
    <div class="ml-container">
        <div class="ml-cta-banner text-center">
            <span class="ml-badge ml-badge-mint mb-3">Community Supported Agriculture</span>
            <h2>Let's Connect &amp; Grow Together</h2>
            <p class="mt-2 mb-4" style="color: rgba(255,255,255,0.85);">Explore MarketLink and discover how local farmers and communities connect through authentic weekly harvests.</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="<?php echo e(url('/markets')); ?>" class="btn ml-btn-primary">Browse Markets</a>
                <a href="<?php echo e(url('/products')); ?>" class="btn ml-btn-secondary">Explore Products</a>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('Website._master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MarketLink2\resources\views/Website/Contact/index.blade.php ENDPATH**/ ?>