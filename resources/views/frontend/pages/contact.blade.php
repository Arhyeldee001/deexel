@extends('frontend.layouts.app')

@section('content')
<section class="section">
    <div class="wrap" style="max-width:960px;">
        <div class="sec-head center reveal">
            <span class="eyebrow">Get in touch</span>
            <h1 class="h-sec" style="margin-top:10px;">Let's build something</h1>
            <p class="lead">Tell us about your project and we'll get back to you shortly.</p>
        </div>

        <div class="contact-grid">
            <div class="contact-form-wrap glass reveal">
                @if(session('ok'))
                    <div class="contact-success">{{ session('ok') }}</div>
                @endif

                @if($errors->any())
                    <div class="contact-error">Please check the form and try again.</div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="contact-form">
                    @csrf
                    {{-- Honeypot: hidden from humans, bots fill it --}}
                    <div style="position:absolute;left:-9999px;" aria-hidden="true">
                        <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    </div>

                    <div class="c-row">
                        <div class="c-fld">
                            <label>Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required>
                            @error('name')<span class="c-err">{{ $message }}</span>@enderror
                        </div>
                        <div class="c-fld">
                            <label>Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required>
                            @error('email')<span class="c-err">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="c-row">
                        <div class="c-fld">
                            <label>Phone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}">
                        </div>
                        <div class="c-fld">
                            <label>Subject</label>
                            <input type="text" name="subject" value="{{ old('subject') }}">
                        </div>
                    </div>

                    <div class="c-fld">
                        <label>Message *</label>
                        <textarea name="message" rows="5" required>{{ old('message') }}</textarea>
                        @error('message')<span class="c-err">{{ $message }}</span>@enderror
                    </div>

                    <button type="submit" class="btn btn-acc">Send message →</button>
                </form>
            </div>

            <div class="contact-info reveal">
                <h3 style="font-size:18px;">Reach us directly</h3>
                @if($email)
                    <div class="ci-item"><span class="mut">Email</span><a href="mailto:{{ $email }}">{{ $email }}</a></div>
                @endif
                @if($phone)
                    <div class="ci-item"><span class="mut">Phone</span><a href="tel:{{ $phone }}">{{ $phone }}</a></div>
                @endif
                <div class="ci-item"><span class="mut">Location</span><span>Lagos, Nigeria</span></div>
            </div>
        </div>
    </div>
</section>

<style>
.contact-grid{display:grid;grid-template-columns:1.6fr 1fr;gap:24px;margin-top:34px;align-items:start;}
.contact-form-wrap{border-radius:18px;padding:clamp(20px,4vw,32px);}
.contact-form{display:flex;flex-direction:column;gap:16px;position:relative;}
.c-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.c-fld{display:flex;flex-direction:column;gap:7px;min-width:0;}
.c-fld label{font-size:13px;color:var(--mut);}
.c-fld input,.c-fld textarea{background:rgba(255,255,255,.05);border:1px solid var(--bd);border-radius:10px;color:var(--tx);padding:12px 14px;font-size:14px;font-family:inherit;outline:none;transition:border-color .2s;width:100%;}
.c-fld input:focus,.c-fld textarea:focus{border-color:var(--acc);}
.c-err{color:#fca5a5;font-size:12px;}
.contact-success{background:rgba(74,222,128,.12);border:1px solid rgba(74,222,128,.35);color:#86efac;padding:12px 14px;border-radius:10px;margin-bottom:16px;font-size:14px;}
.contact-error{background:rgba(248,113,113,.12);border:1px solid rgba(248,113,113,.35);color:#fca5a5;padding:12px 14px;border-radius:10px;margin-bottom:16px;font-size:14px;}
.contact-info{padding:8px 4px;}
.ci-item{display:flex;flex-direction:column;gap:2px;margin-top:16px;font-size:14px;}
.ci-item .mut{font-size:12px;text-transform:uppercase;letter-spacing:.05em;}
.ci-item a{color:var(--acc);}
@media (max-width:760px){.contact-grid{grid-template-columns:1fr;}.c-row{grid-template-columns:1fr;}}
</style>
@endsection
