<?php
function loginFormHtml($HOST_NAME)
{
echo'
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login – ConvertRank</title>
  <meta name="color-scheme" content="light" />
  <meta name="description" content="Login to ConvertRank using Google." />

  <!-- Brand tokens (light) -->
  <style>
    :root{
      --bg:#ffffff; --ink:#0b0b0c; --muted:#5f6368; --line:#e9eaee;
      --card:#ffffff; --soft:#f7f7f9; --shadow:0 10px 30px rgba(0,0,0,.06);
      --brand-primary:#5a2c68; --brand-secondary:#a9963e;
      --maxw:840px; --r:16px; --space:clamp(18px,2vw,28px);
    }
    [data-theme="dark"]{
      --bg:#0b0b0c; --ink:#f2f3f5; --muted:#a6abb4; --line:#1d1f26;
      --card:#0f1012; --soft:#111214; --shadow:0 12px 40px rgba(0,0,0,.45);
    }

    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0; font-family:"Open Sans","Inter",system-ui,-apple-system,Segoe UI,Roboto,"Helvetica Neue",Arial,sans-serif;
      color:var(--ink); background:var(--bg);
      -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale;
    }
    a{color:inherit; text-decoration:none}
    .container{max-width:var(--maxw); margin:0 auto; padding:0 var(--space)}

    /* Header (simple) */
    header{ position:sticky; top:0; z-index:10; background:rgba(255,255,255,.9); backdrop-filter:saturate(140%) blur(8px); border-bottom:1px solid var(--line); }
    [data-theme="dark"] header{ background:rgba(15,16,18,.7) }
    .head{ display:flex; align-items:center; justify-content:space-between; padding:14px 0 }
    .brand{ display:flex; align-items:center; gap:12px; color:var(--brand-primary); font-weight:600 }
    .mark{ width:34px; height:34px; border-radius:8px; display:grid; place-items:center; background:var(--brand-primary); color:#fff; font-weight:700 }

    /* Google Login Btn */
      .google-btn{display:inline-flex;gap:10px;align-items:center;border:1px solid #dadce0;padding:10px 18px;border-radius:4px;text-decoration:none;color:#3c4043;background:#fff}
      .google-btn:hover{box-shadow:0 1px 2px rgba(0,0,0,.1)}
      .google-btn img{width:18px;height:18px}

    /* Card */
    .wrap{ min-height:calc(100vh - 64px); display:grid; place-items:center; padding:clamp(32px,6vw,80px) 0 }
    .card{ width:min(640px,100%); background:var(--card); border:1px solid var(--line); border-radius:var(--r); box-shadow:var(--shadow); padding:clamp(18px,3vw,28px) }
    h1{ margin:0 0 8px; font-weight:300; letter-spacing:-.01em; color:var(--brand-primary); font-size:clamp(26px,4.6vw,36px) }
    p{ margin:8px 0 0; color:var(--muted); line-height:1.7 }
    .muted{ background:var(--soft); border-radius:12px; padding:14px; border:1px solid var(--line); margin-top:14px }

    .btn{ display:inline-flex; align-items:center; justify-content:center; gap:10px; padding:10px 14px; border:1px solid var(--line); border-radius:10px; cursor:pointer }
    .btn-secondary{ background:var(--brand-secondary); color:#fff; border:none }
    .row{ display:flex; gap:10px; align-items:center; flex-wrap:wrap }
    .center{ text-align:center }

    .footer{ margin-top:18px; display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; color:var(--muted); font-size:14px }
  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <div class="container head" style="padding-left:10px; padding-right:10px;">
      <a href="/" class="brand" aria-label="ConvertRank Home">
        <div class="mark">CR</div><div>ConvertRank</div>
      </a>
      <a href="/" class="btn">← Back to site</a>
    </div>
  </header>

  <!-- Login Card -->
  <main class="wrap">
    <div class="container">
      <div class="card" role="dialog" aria-labelledby="loginTitle" aria-describedby="loginDesc">
        <h1 id="loginTitle">Sign in to ConvertRank</h1>
        <p id="loginDesc">Use your Google account to access your projects and traffic plans.</p>
        <p></p>
          <a class="google-btn" href="',$HOST_NAME,'/google-login.php">
          <img src="https://developers.google.com/identity/images/g-logo.png" alt="G">
          Sign in with Google
        </a>

        <div class="footer">
          <div>© <span id="year"></span> ConvertRank</div>
          <div class="row">
            <a href="/privacy">Privacy</a><span aria-hidden="true">•</span>
            <a href="/terms">Terms</a><span aria-hidden="true">•</span>
            <a href="/contact">Contact</a>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>
</html>
';
}
?>
