<?php


add_shortcode(
'user_register',
'cup_register_form'
);

function cup_register_form() {


    global $wpdb;

    $message = '';
    $status  = '';


    if(isset($_POST['cup_register'])) {


        $table = $wpdb->prefix . 'custom_users';


        $name = sanitize_text_field(
            $_POST['cup_name']
        );


        $email = sanitize_email(
            $_POST['cup_email']
        );


        $password = $_POST['cup_password'];



        // Check duplicate email only
        $exists = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT id FROM $table WHERE email=%s",
                $email
            )
        );


        if($exists){


            $message = "Email already registered";
            $status = "error";


        } else {$result = $wpdb->insert(
    $table,
    array(
        'name'     => $name,
        'email'    => $email,
        'password' => password_hash(
            $password,
            PASSWORD_DEFAULT
        )
    ),
    array(
        '%s',
        '%s',
        '%s'
    )
);


if ($result === false) {

    $message = "Database Error: " . $wpdb->last_error;
    $status = "error";

} else {

    $message = "Registration successful";
    $status = "success";
    wp_safe_redirect(home_url('/signin/'));

}
        }

    }

ob_start();
?>








<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create account · ReadNest</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,500&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&family=Caveat:wght@500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#1B2430; --ink-soft:#2A3543;
    --paper:#EDE8DC; --paper-card:#F7F4EC;
    --brass:#AD8A4E; --brass-dark:#8B6D3A;
    --teal:#3E6259; --danger:#9A4B3E;
    --line: rgba(27,36,48,.14);
    --line-light: rgba(247,244,236,.18);
    --radius:3px;
  }
  *{box-sizing:border-box;}
  html,body{margin:0;padding:0;}
  body{ background:var(--paper); color:var(--ink); font-family:'Inter',sans-serif; min-height:100vh; -webkit-font-smoothing:antialiased; }
  ::selection{ background:var(--brass); color:var(--paper-card); }

  .auth-shell{ min-height:100vh; display:grid; grid-template-columns:1.15fr 1fr; }
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
  .field .hint{ font-family:'IBM Plex Mono', monospace; font-size:10.5px; color:rgba(27,36,48,.4); font-weight:400; margin-left:6px; }
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

  .strength{ display:flex; gap:4px; margin-top:8px; }
  .strength i{ flex:1; height:3px; background:var(--line); border-radius:2px; }
  .strength.s1 i:nth-child(1){ background:var(--danger); }
  .strength.s2 i:nth-child(1), .strength.s2 i:nth-child(2){ background:var(--brass); }
  .strength.s3 i{ background:var(--teal); }

  .row-between{ display:flex; align-items:center; justify-content:space-between; margin:6px 0 24px; }
  .checkline{ display:flex; align-items:center; gap:8px; font-size:13px; color:var(--ink-soft); }
  .checkline input{ accent-color:var(--brass); width:15px; height:15px; }

  .btn-primary{
    width:100%; background:var(--ink); color:var(--paper-card); border:none;
    font-family:'Inter',sans-serif; font-size:15px; font-weight:600; letter-spacing:.01em;
    padding:13px 0; border-radius:2px; cursor:pointer; transition:background .15s ease, transform .1s ease;
  }
  .btn-primary:hover{ background:var(--brass-dark); }
  .btn-primary:active{ transform:translateY(1px); }
  .btn-primary:focus-visible{ outline:2px solid var(--brass); outline-offset:2px; }

  .switch-line{ text-align:center; font-size:13.5px; color:rgba(27,36,48,.65); margin-top:22px; }
  .switch-line a{ color:var(--brass-dark); font-weight:600; font-size:13.5px; font-family:'Inter',sans-serif; text-decoration:none; border-bottom:1px solid rgba(139,109,58,.4); }

  @media (prefers-reduced-motion: reduce){ *{ transition:none !important; } }
</style>



    <div class="auth-shell">
    <div class="auth-form-wrap">

    
    <form class="card" id="registerForm" novalidate method="post">
        <?php wp_nonce_field('cup_register_action','cup_register_nonce'); ?>

      <p class="eyebrow">First edition</p>
      <h1>Start your shelf</h1>
      <p class="sub">A private place to keep what you underline.</p>
      
      <div id="formMessage" class="cup-message"></div>
      <?php if(!empty($message)): ?>

<div class="cup-message <?php echo esc_attr($status); ?>">
    <?php echo esc_html($message); ?>
</div>

<?php endif; ?>
      
      <div class="field">
        <label for="re-name">Full name</label>
        <input id="re-name" name="cup_name" type="text" placeholder="Enter your full name" autocomplete="name" required>
      </div>
      
      <div class="field">
        <label for="re-email">Email</label>
        <input id="re-email" name="cup_email" type="email" placeholder="Enter your email" autocomplete="email" required>
      </div>
      <div class="field">
        <label for="re-pass">Password <span class="hint">min. 8 characters</span></label>
        <input id="re-pass" name="cup_password" type="password" placeholder="Enter your password" autocomplete="new-password" required>
        <div class="strength" id="strengthMeter"><i></i><i></i><i></i></div>
      </div>


      <div class="field">
        <label for="re-pass1">Confirm Password </label>
        <input id="re-pass1" name="cup_confirm_password" type="password" placeholder="Confirm your password" autocomplete="new-password" required>
        <div class="strength" id="strengthMeter"><i></i><i></i><i></i></div>
      </div>
      
      <div class="row-between">
        <label class="checkline"><input type="checkbox" required> I agree to the terms &amp; reading policy</label>
      </div>
      <button type="submit" name="cup_register" class="btn-primary">Create account</button>
      <p class="switch-line">Already keeping notes? <a href="<?php echo home_url('/signin/'); ?>">Sign in instead</a></p>
    </form>
  </div>
  <aside class="auth-side">
    <div class="wordmark">Read<span>Nest</span></div>
    <div class="marginal-note">
      <span class="mark">First flyleaf</span>
      <p>The best marginalia isn't tidy. It argues, agrees, wanders off.</p>
      <span class="scrawl">— write in the margins, always</span>
    </div>
    <div class="footer-meta">
      <span>Vol. I</span>
      <span>No two shelves alike</span>
    </div>
  </aside>
</div>


<?php

    return ob_get_clean();
}

?>
