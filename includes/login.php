<?php


add_shortcode(
'user_login',
'cup_login_form'
);

function cup_login_form() {


    global $wpdb;

    $message = '';
    $status  = '';


    if (isset($_POST['cup_login'])) {

    if (
        !isset($_POST['cup_login_nonce']) ||
        !wp_verify_nonce($_POST['cup_login_nonce'], 'cup_login_action')
    ) {
        $message = "Invalid request.";
        $status = "error";
    } else {

        $table = $wpdb->prefix . 'custom_users';

        $email = sanitize_email($_POST['cup_email']);
        $password = $_POST['cup_password'];

        $user = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table WHERE email=%s",
                $email
            )
        );

        if (!$user) {

            $message = "Email not found.";
            $status = "error";

        } elseif (!password_verify($password, $user->password)) {

            $message = "Incorrect password.";
            $status = "error";

        } else {

            // Start session if not already started
            if (!session_id()) {
                session_start();
            }

            $_SESSION['cup_user_id'] = $user->id;
            $_SESSION['cup_user_name'] = $user->name;
            $_SESSION['cup_user_email'] = $user->email;

            wp_safe_redirect(home_url('/profile/'));
            exit;
        }
    }
}

ob_start();
?>


<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign in · ReadNest</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,500&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&family=Caveat:wght@500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#1B2430; --ink-soft:#2A3543;
    --paper:#EDE8DC; --paper-card:#F7F4EC;
    --brass:#AD8A4E; --brass-dark:#8B6D3A;
    --teal:#3E6259;
    --line: rgba(27,36,48,.14);
    --line-light: rgba(247,244,236,.18);
    --radius:3px;
  }
  *{box-sizing:border-box;}
  html,body{margin:0;padding:0;}
  body{ background:var(--paper); color:var(--ink); font-family:'Inter',sans-serif; min-height:100vh; -webkit-font-smoothing:antialiased; }
  ::selection{ background:var(--brass); color:var(--paper-card); }

  .auth-shell{ min-height:100vh; display:grid; grid-template-columns:1fr 1.15fr; }
  @media (max-width:860px){ .auth-shell{ grid-template-columns:1fr; } .auth-side{ display:none; } }

  .auth-side{
    background:var(--ink); color:var(--paper-card); position:relative;
    display:flex; flex-direction:column; justify-content:space-between;
    padding:56px 52px; overflow:hidden;
  }
  .auth-side::before{
    content:""; position:absolute; inset:0;
    background:repeating-linear-gradient(180deg, transparent 0 38px, var(--line-light) 38px 39px);
    opacity:.5; pointer-events:none;
  }
  .wordmark{ font-family:'Fraunces', serif; font-size:28px; font-weight:500; position:relative; z-index:1; }
  .wordmark span{ color:var(--brass); font-style:italic; }
  .marginal-note{ position:relative; z-index:1; max-width:360px; }
  .marginal-note .mark{
    font-family:'IBM Plex Mono', monospace; font-size:11px; letter-spacing:.1em;
    text-transform:uppercase; color:var(--brass); display:block; margin-bottom:14px;
  }
  .marginal-note p{ font-family:'Fraunces', serif; font-size:26px; line-height:1.35; font-weight:400; margin:0 0 18px; }
  .marginal-note .scrawl{ font-family:'Caveat', cursive; font-size:22px; color:var(--brass); transform:rotate(-2deg); display:inline-block; }
  .auth-side .footer-meta{
    position:relative; z-index:1; display:flex; justify-content:space-between;
    font-family:'IBM Plex Mono', monospace; font-size:11px; color:rgba(247,244,236,.45);
    letter-spacing:.05em; border-top:1px solid var(--line-light); padding-top:16px;
  }

  .auth-form-wrap{ display:flex; align-items:center; justify-content:center; padding:40px 32px; }
  .card{
    width:100%; max-width:420px; background:var(--paper-card);
    border:1px solid var(--line); border-radius:var(--radius);
    padding:44px 40px 36px; position:relative; box-shadow:0 1px 2px rgba(27,36,48,.04);
  }
  .card::before{
    content:""; position:absolute; top:0; right:0; width:26px; height:26px;
    background:linear-gradient(135deg, transparent 50%, var(--paper) 50%), var(--paper-card);
    box-shadow:-2px 2px 4px rgba(27,36,48,.12); border-bottom-left-radius:2px;
  }
  .card .eyebrow{ font-family:'IBM Plex Mono', monospace; font-size:11px; letter-spacing:.12em; text-transform:uppercase; color:var(--brass-dark); margin:0 0 8px; }
  .card h1{ font-family:'Fraunces', serif; font-size:32px; font-weight:500; margin:0 0 8px; line-height:1.1; }
  .card .sub{ font-size:14.5px; color:rgba(27,36,48,.62); margin:0 0 30px; line-height:1.5; }

  .field{ margin-bottom:20px; }
  .field label{ display:block; font-size:12.5px; font-weight:500; margin-bottom:7px; color:var(--ink-soft); }
  .field input{
    width:100%; font-family:'Inter',sans-serif; font-size:15px; padding:11px 13px;
    background:var(--paper); border:1px solid var(--line); border-radius:2px; color:var(--ink);
    transition:border-color .15s ease, background .15s ease;
  }
  .field input::placeholder{ color:rgba(27,36,48,.35); }
  .field input:hover{ border-color:rgba(27,36,48,.3); }
  .field input:focus-visible, .field input:focus{
    outline:none; border-color:var(--brass); background:var(--paper-card);
    box-shadow:0 0 0 3px rgba(173,138,78,.18);
  }

  .row-between{ display:flex; align-items:center; justify-content:space-between; margin:-6px 0 24px; }
  .checkline{ display:flex; align-items:center; gap:8px; font-size:13px; color:var(--ink-soft); }
  .checkline input{ accent-color:var(--brass); width:15px; height:15px; }
  .link{ color:var(--brass-dark); font-size:13px; text-decoration:none; font-weight:500; border-bottom:1px solid rgba(139,109,58,.35); }
  .link:hover{ border-color:var(--brass-dark); }

  .btn-primary{
    width:100%; background:var(--ink); color:var(--paper-card); border:none;
    font-family:'Inter',sans-serif; font-size:15px; font-weight:600; letter-spacing:.01em;
    padding:13px 0; border-radius:2px; cursor:pointer; transition:background .15s ease, transform .1s ease;
  }
  .btn-primary:hover{ background:var(--brass-dark); }
  .btn-primary:active{ transform:translateY(1px); }
  .btn-primary:focus-visible{ outline:2px solid var(--brass); outline-offset:2px; }

  .divider{
    display:flex; align-items:center; gap:14px; margin:26px 0 20px;
    color:rgba(27,36,48,.4); font-family:'IBM Plex Mono', monospace; font-size:10.5px;
    text-transform:uppercase; letter-spacing:.1em;
  }
  .divider::before, .divider::after{ content:""; flex:1; height:1px; background:var(--line); }
  .btn-secondary{
    background:none; border:1px solid var(--line); color:var(--ink);
    font-family:'Inter',sans-serif; font-weight:600; font-size:14px;
    padding:11px 22px; border-radius:2px; cursor:pointer; width:100%;
  }
  .btn-secondary:hover{ border-color:var(--ink); }

  .switch-line{ text-align:center; font-size:13.5px; color:rgba(27,36,48,.65); margin-top:22px; }
  .switch-line a{ color:var(--brass-dark); font-weight:600; font-size:13.5px; font-family:'Inter',sans-serif; text-decoration:none; border-bottom:1px solid rgba(139,109,58,.4); }

  @media (prefers-reduced-motion: reduce){ *{ transition:none !important; } }
</style>



<div class="auth-shell">
  <aside class="auth-side">
    <div class="wordmark">Read<span>Nest</span></div>
    <div class="marginal-note">
      <span class="mark">Reader's note — pg. 214</span>
      <p>Every book you finish leaves a residue of yourself in the margins.</p>
      <span class="scrawl">— keep the thread going</span>
    </div>
    <div class="footer-meta">
      <span>Vol. III</span>
      <span>Est. reading, well kept</span>
    </div>
  </aside>
  <div class="auth-form-wrap">
    <form class="card" novalidate method="post">

    <?php wp_nonce_field('cup_login_action','cup_login_nonce'); ?>

      <p class="eyebrow">Welcome back</p>
      <h1>Open your shelf</h1>
      <p class="sub">Sign in to pick up exactly where you left a note.</p>

<div id="formMessage" class="cup-message"></div>
<?php if(!empty($message)): ?>
<div class="cup-message <?php echo esc_attr($status); ?>">
<?php echo esc_html($message); ?>
</div>
<?php endif; ?>


      <div class="field">
        <label for="li-email">Email</label>
        <input id="li-email" name="cup_email" type="email" placeholder="Enter your email" autocomplete="email" required>
      </div>
      <div class="field">
        <label for="li-pass">Password <span class="hint">min. 8 characters</span></label>
        <input id="li-pass" name="cup_password" type="password" placeholder="Enter your password" required>
      </div>

      
      <!-- <div class="row-between">
        <label class="checkline"><input type="checkbox"> Stay signed in</label>
        <a class="link" href="#">Forgot it?</a>
      </div> -->
      <button type="submit" class="btn-primary" name="cup_login">Sign in</button>
      <div class="divider">or</div>
      <p class="switch-line">New to ReadNest? <a href="<?php echo home_url('/signup/'); ?>">Start an account</a></p>
    </form>
  </div>
</div>



<?php

    return ob_get_clean();
}

?>
