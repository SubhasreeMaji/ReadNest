<?php
add_shortcode(
'user_profile',
'cup_profile_form'
);
function cup_profile_form() {

    if (!session_id()) {
        session_start();
    }

    if (!isset($_SESSION['cup_user_id'])) {
        wp_safe_redirect(home_url('/signin/'));
        exit;
    }

    if (
        isset($_POST['cup_logout']) &&
        isset($_POST['cup_logout_nonce']) &&
        wp_verify_nonce($_POST['cup_logout_nonce'], 'cup_logout_action')
    ) {

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        wp_safe_redirect(home_url('/signin/'));
        exit;
    }
    ob_start();
?>



<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profile details · ReadNest</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,500&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#1B2430; --ink-soft:#2A3543;
    --paper:#EDE8DC; --paper-card:#F7F4EC;
    --brass:#AD8A4E; --brass-dark:#8B6D3A;
    --teal:#3E6259;
    --line: rgba(27,36,48,.14);
    --radius:3px;
  }
  *{box-sizing:border-box;}
  html,body{margin:0;padding:0;}
  body{ background:var(--paper); color:var(--ink); font-family:'Inter',sans-serif; min-height:100vh; -webkit-font-smoothing:antialiased; }
  ::selection{ background:var(--brass); color:var(--paper-card); }

  .profile-shell{ min-height:100vh; padding:56px 24px 80px; }
  .profile-header{ max-width:760px; margin:0 auto 8px; display:flex; align-items:baseline; justify-content:space-between; }
  .profile-header .wm{ font-family:'Fraunces', serif; font-size:20px; color:var(--ink); }
  .profile-header .wm span{ color:var(--brass-dark); font-style:italic; }
  .profile-header .page-of{ font-family:'IBM Plex Mono', monospace; font-size:11px; color:rgba(27,36,48,.4); letter-spacing:.05em; }

  .journal{
    max-width:760px; margin:24px auto 0;
    background:var(--paper-card); border:1px solid var(--line); border-radius:var(--radius);
    position:relative; box-shadow:0 1px 2px rgba(27,36,48,.04);
  }
  .journal::before{
    content:""; position:absolute; top:0; right:0; width:30px; height:30px;
    background:linear-gradient(135deg, transparent 50%, var(--paper) 50%), var(--paper-card);
    box-shadow:-2px 2px 5px rgba(27,36,48,.12);
  }

  .journal-head{ padding:40px 48px 0; }
  .journal-head .eyebrow{ font-family:'IBM Plex Mono', monospace; font-size:11px; letter-spacing:.12em; text-transform:uppercase; color:var(--brass-dark); margin:0 0 8px; }
  .journal-head h1{ font-family:'Fraunces', serif; font-weight:500; font-size:34px; margin:0 0 10px; }
  .journal-head p{ font-size:14.5px; color:rgba(27,36,48,.6); max-width:520px; line-height:1.5; margin:0; }

  .body{ padding:8px 48px 44px; }

  .section{ padding:32px 0; border-bottom:1px solid var(--line); }
  .section:first-child{ padding-top:32px; }
  .section:last-of-type{ border-bottom:none; }
  .section-num{
    font-family:'IBM Plex Mono', monospace; font-size:11px; color:var(--brass-dark);
    letter-spacing:.1em; margin:0 0 4px;
  }
  .section-title{ font-family:'Fraunces', serif; font-size:20px; font-weight:500; margin:0 0 6px; }
  .section-desc{ font-size:13.5px; color:rgba(27,36,48,.55); margin:0 0 22px; line-height:1.5; max-width:480px; }

  .field-p{ margin-bottom:20px; }
  .field-p:last-child{ margin-bottom:0; }
  .field-p label{ display:block; font-size:12.5px; font-weight:500; margin-bottom:7px; color:var(--ink-soft); }
  .field-p .hint{ font-family:'IBM Plex Mono', monospace; font-size:10.5px; color:rgba(27,36,48,.4); font-weight:400; margin-left:6px; }
  .field-p input, .field-p select, .field-p textarea{
    width:100%; font-family:'Inter',sans-serif; font-size:14.5px; padding:11px 13px;
    background:var(--paper); border:1px solid var(--line); border-radius:2px; color:var(--ink);
  }
  .field-p textarea{ resize:vertical; min-height:88px; }
  .field-p input:focus-visible, .field-p select:focus-visible, .field-p textarea:focus-visible,
  .field-p input:focus, .field-p select:focus, .field-p textarea:focus{
    outline:none; border-color:var(--brass); background:var(--paper-card);
    box-shadow:0 0 0 3px rgba(173,138,78,.18);
  }

  .avatar-row{ display:flex; align-items:center; gap:18px; margin-bottom:24px; }
  .avatar{
    width:60px; height:60px; border-radius:50%; background:var(--ink); color:var(--paper-card);
    display:flex; align-items:center; justify-content:center; font-family:'Fraunces', serif; font-size:20px;
    border:2px solid var(--brass); flex-shrink:0;
  }
  .avatar-row .btn-ghost{
    font-family:'Inter',sans-serif; font-size:13px; font-weight:600; background:none;
    border:1px solid var(--line); color:var(--ink); padding:8px 14px; border-radius:2px; cursor:pointer;
  }
  .avatar-row .btn-ghost:hover{ border-color:var(--brass); color:var(--brass-dark); }

  .tag-group{ display:flex; flex-wrap:wrap; gap:8px; }
  .tag{
    font-family:'IBM Plex Mono', monospace; font-size:12px; padding:7px 13px; border-radius:999px;
    border:1px solid var(--line); background:var(--paper); cursor:pointer; color:var(--ink-soft);
    transition:all .15s ease;
  }
  .tag.on{ background:var(--teal); border-color:var(--teal); color:var(--paper-card); }
  .tag:focus-visible{ outline:2px solid var(--brass); outline-offset:2px; }

  .grid2{ display:grid; grid-template-columns:1fr 1fr; gap:20px; }
  @media (max-width:640px){ .grid2{ grid-template-columns:1fr; } }

  .save-bar{
    display:flex; align-items:center; justify-content:space-between;
    padding:22px 48px; border-top:1px solid var(--line);
  }
  .save-bar .note{ font-family:'IBM Plex Mono', monospace; font-size:11px; color:rgba(27,36,48,.4); }
  .save-bar .actions{ display:flex; gap:12px; }
  .btn-secondary{
    background:none; border:1px solid var(--line); color:var(--ink); font-family:'Inter',sans-serif;
    font-weight:600; font-size:14px; padding:11px 22px; border-radius:2px; cursor:pointer;
  }
  .btn-secondary:hover{ border-color:var(--ink); }
  .btn-primary{
    background:var(--ink); color:var(--paper-card); border:none; font-family:'Inter',sans-serif;
    font-weight:600; font-size:14px; padding:11px 26px; border-radius:2px; cursor:pointer;
    transition:background .15s ease;
  }
  .btn-primary:hover{ background:var(--brass-dark); }
  .btn-primary:focus-visible, .btn-secondary:focus-visible{ outline:2px solid var(--brass); outline-offset:2px; }

  @media (max-width:640px){
    .journal-head, .body, .save-bar{ padding-left:24px; padding-right:24px; }
    .save-bar{ flex-direction:column; gap:14px; align-items:stretch; }
  }
  @media (prefers-reduced-motion: reduce){ *{ transition:none !important; } }
</style>
</head>
<body>

<div class="profile-shell">
  <div class="profile-header">
    <div class="wm">Read<span>Nest</span></div>
    <div class="page-of">Your entry</div>
  </div>

  <div class="journal">
    <div class="journal-head">
      <p class="eyebrow">Chapter 04</p>
      <h1>Profile details</h1>
      <p>This is what other readers see, and what shapes the recommendations on your shelf.</p>
    </div>
<form method="post" style="display:inline;">
      <?php wp_nonce_field('cup_logout_action','cup_logout_nonce'); ?>
    <div class="body">
        <!-- Section 1: About you -->
      <div class="section">
        <p class="section-num">01</p>
        <h2 class="section-title">About you</h2>
        <p class="section-desc">Your name and photo appear on notes you leave for other readers.</p>

        <div class="avatar-row">
          <div class="avatar">AL</div>
          <button class="btn-ghost" type="button">Change photo</button>
        </div>

        <div class="field-p">
          <label for="pf-first">First name</label>
          <input id="pf-first" type="text" value="Ada">
        </div>
        <div class="field-p">
          <label for="pf-last">Last name</label>
          <input id="pf-last" type="text" value="Lovelace">
        </div>
        <div class="field-p">
          <label for="pf-handle">Display handle</label>
          <input id="pf-handle" type="text" value="@marginalia_ada">
        </div>
        <div class="field-p">
          <label for="pf-bio">Short bio <span class="hint">optional, 160 char</span></label>
          <textarea id="pf-bio" placeholder="What do you mostly read, and why do you write in the margins?">Mostly annotate essays and old sci-fi. Underliner, not a highlighter.</textarea>
        </div>
      </div>


    </div>

    <div class="save-bar">
      <span class="note">Last saved 3 minutes ago</span>
      <div class="actions">
        
<button type="submit" name="cup_logout" class="btn-secondary">Logout</button>
        <button class="btn-secondary" type="button">Discard</button>
        <button class="btn-primary" type="button">Save changes</button>
      </div>
    </div>

    </form>
  </div>
</div>
<script>
  document.querySelectorAll('#genreTags .tag').forEach(tag=>{
    tag.addEventListener('click', ()=> tag.classList.toggle('on'));
  });
</script>


<?php
    return ob_get_clean();
}
?>