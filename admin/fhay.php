<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Hi Fhay D. Clems – Animated</title>
  <style>
    :root {
      --bg1: #0f1226; /* deep indigo */
      --bg2: #101c3c; /* navy */
      --accent: #9a8cff; /* soft violet */
      --glow: 0 0 20px rgba(154, 140, 255, .55), 0 0 40px rgba(154, 140, 255, .25);
    }

    /* Reset & layout */
    * { box-sizing: border-box; }
    html, body { height: 100%; margin: 0; }
    body {
      display: grid; place-items: center; min-height: 100vh;
      color: white; background: radial-gradient(1200px 800px at 20% 20%, #1b2050 0%, transparent 60%),
                  radial-gradient(1200px 800px at 80% 80%, #12163a 0%, transparent 60%),
                  linear-gradient(135deg, var(--bg1), var(--bg2));
      font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji";
    }

    .wrap {
      text-align: center;
      padding: 24px 28px;
    }

    .greet {
      font-size: clamp(28px, 8vw, 72px);
      font-weight: 800;
      letter-spacing: .02em;
      line-height: 1.1;
      margin: 0; white-space: nowrap;
      filter: drop-shadow(0 8px 24px rgba(0,0,0,.5));
    }

    .greet span {
      display: inline-block;
      opacity: 0; transform: translateY(18px) scale(.96) rotate(-1deg);
      animation: popIn .6s cubic-bezier(.2,.8,.2,1) forwards;
      will-change: transform, opacity, text-shadow;
      text-shadow: 0 0 0 rgba(154, 140, 255, 0);
    }

    .accent {
      background: linear-gradient(90deg, #a5b4fc, #c084fc, #a78bfa);
      -webkit-background-clip: text; background-clip: text; color: transparent;
    }

    /* Cursor / underline flourish */
    .cursor {
      display: inline-block; width: .07em; height: .9em; margin-left: .12em;
      background: currentColor; opacity: .85; vertical-align: -.1em;
      animation: blink 1s steps(1) infinite;
    }

    /* Replay button */
    .actions { margin-top: 18px; }
    button {
      appearance: none; border: 0; cursor: pointer; border-radius: 14px;
      padding: 10px 14px; font-weight: 600; font-size: 14px;
      color: #0e1028; background: #c7d2fe;
      box-shadow: 0 8px 24px rgba(69, 64, 255, .25);
      transition: transform .15s ease, box-shadow .15s ease, background .2s ease;
    }
    button:hover { transform: translateY(-1px); box-shadow: 0 10px 28px rgba(69, 64, 255, .3); background: #a5b4fc; }
    button:active { transform: translateY(0); box-shadow: 0 6px 18px rgba(69, 64, 255, .25); }

    @keyframes popIn {
      0%   { opacity: 0; transform: translateY(18px) scale(.96) rotate(-2deg); text-shadow: 0 0 0 rgba(154, 140, 255, 0); }
      70%  { opacity: 1; transform: translateY(-2px) scale(1.04) rotate(.4deg); text-shadow: var(--glow); }
      100% { opacity: 1; transform: translateY(0) scale(1) rotate(0deg); text-shadow: 0 0 0 rgba(0,0,0,0); }
    }

    @keyframes blink {
      0%, 49% { opacity: .85; }
      50%, 100% { opacity: 0; }
    }

    /* Respect reduced motion */
    @media (prefers-reduced-motion: reduce) {
      .greet span { animation: none; opacity: 1; transform: none; }
      .cursor { display: none; }
      button { transition: none; }
    }
  </style>
</head>
<body>
  <main class="wrap">
    <h1 class="greet" id="greet"></h1>
    <div class="actions">
      <button id="replay" aria-label="Replay animation">Replay</button>
    </div>
  </main>

  <script>
    // ===== Editable text and timing =====
    const TEXT = "Hi Fhay D. Clems";     // The message
    const STAGGER = 65;                    // ms between each character animation
    const HOLD_CURSOR_MS = 1600;           // how long to keep the blinking cursor after last letter

    // const greet = document.getElementById('greet');
    // const replayBtn = document.getElementById('replay');

    const greet = document.getElementByid('greet');
    const replayBtn = document.getElementById('reply');

    function animateText() {
      // Reset content
      greet.innerHTML = '';

      // Build spans with per-letter delay
      [...TEXT].forEach((ch, i) => {
        const span = document.createElement('span');
        // Accent only the name portion for a nice effect
        if (i >= 3) span.classList.add('accent'); // after "Hi"
        span.textContent = ch;
        span.style.animationDelay = `${i * STAGGER}ms`;
        greet.appendChild(span);
      });

      // Add a blinking cursor at the end
      const cursor = document.createElement('span');
      cursor.className = 'cursor';
      greet.appendChild(cursor);

      // Remove cursor after animation completes
      const total = TEXT.length * STAGGER + HOLD_CURSOR_MS;
      window.clearTimeout(animateText._cursorTimeout);
      animateText._cursorTimeout = window.setTimeout(() => {
        cursor.remove();
      }, total);
    }

    // Kick it off
    animateText();

    // Replay
    replayBtn.addEventListener('click', () => {
      animateText();
    });
  </script>
</body>
</html>
