<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technical Architecture | College Sports Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #05080f;
            --surf: #0a0f1a;
            --surf2: #0e1525;
            --border: #162035;
            --cyan: #00d4ff;
            --cyan-d: rgba(0, 212, 255, .1);
            --green: #00e676;
            --green-d: rgba(0, 230, 118, .08);
            --orange: #ff9100;
            --orng-d: rgba(255, 145, 0, .08);
            --purple: #b06cff;
            --purp-d: rgba(176, 108, 255, .1);
            --amber: #ffc400;
            --slate: #4a6278;
            --text: #c9d6e3;
            --text-dim: #4a6278;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(0, 212, 255, .022) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 212, 255, .022) 1px, transparent 1px);
            background-size: 42px 42px;
            pointer-events: none;
            z-index: 0;
        }

        .wrap {
            position: relative;
            z-index: 1;
            max-width: 1260px;
            margin: 0 auto;
            padding: 60px 28px
        }

        /* ── HEADER ── */
        .pg-header {
            text-align: center;
            margin-bottom: 70px;
            padding-bottom: 50px;
            border-bottom: 1px solid var(--border)
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: .65rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--green);
            background: var(--green-d);
            border: 1px solid rgba(0, 230, 118, .3);
            padding: 5px 16px;
            border-radius: 20px;
            margin-bottom: 20px;
        }

        .badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--green);
            animation: blink 2s infinite
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .2
            }
        }

        h1 {
            font-size: clamp(2rem, 5vw, 3.6rem);
            font-weight: 800;
            background: linear-gradient(135deg, #c9d6e3 0%, #00d4ff 40%, #b06cff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 14px;
        }

        .pg-sub {
            color: var(--text-dim);
            font-size: .8rem;
            letter-spacing: 2px;
            text-transform: uppercase
        }

        /* ── SECTION ── */
        .sec {
            background: var(--surf);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 36px;
            margin-bottom: 44px;
            position: relative;
            overflow: hidden;
        }

        .sec::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--cyan), transparent);
        }

        .sec-tag {
            font-size: .6rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--purple);
            background: var(--purp-d);
            border: 1px solid rgba(176, 108, 255, .3);
            padding: 3px 12px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 10px;
        }

        .sec-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 6px
        }

        .sec-desc {
            font-size: .8rem;
            color: var(--text-dim);
            border-left: 3px solid var(--border);
            padding-left: 14px;
            margin-bottom: 24px;
            line-height: 1.7
        }

        /* ── SVG CANVAS ── */
        .svgwrap {
            width: 100%;
            overflow-x: auto
        }

        .svgwrap svg {
            display: block;
            overflow: visible
        }

        /* ── LEGEND ── */
        .legend {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 20px;
            padding: 14px 18px;
            background: rgba(0, 0, 0, .3);
            border-radius: 8px;
            border: 1px solid var(--border)
        }

        .li {
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'JetBrains Mono', monospace;
            font-size: .68rem;
            color: var(--text-dim);
            letter-spacing: .4px
        }

        .ls {
            width: 24px;
            height: 13px;
            border-radius: 3px;
            border: 1.5px solid;
            flex-shrink: 0
        }

        /* ── ANALYSIS GRID ── */
        .agrid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 0
        }

        @media(max-width:700px) {
            .agrid {
                grid-template-columns: 1fr
            }
        }

        .acard {
            background: rgba(0, 0, 0, .35);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            position: relative;
            overflow: hidden
        }

        .acard::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 3px
        }

        .acard.c1::before {
            background: var(--cyan)
        }

        .acard.c2::before {
            background: var(--purple)
        }

        .acard.c3::before {
            background: var(--green)
        }

        .acard.c4::before {
            background: var(--orange)
        }

        .acard h4 {
            font-size: .9rem;
            font-weight: 700;
            margin-bottom: 12px
        }

        .acard.c1 h4 {
            color: var(--cyan)
        }

        .acard.c2 h4 {
            color: var(--purple)
        }

        .acard.c3 h4 {
            color: var(--green)
        }

        .acard.c4 h4 {
            color: var(--orange)
        }

        .acard p {
            font-size: .8rem;
            color: var(--text-dim);
            line-height: 1.75
        }

        .acard code {
            font-family: 'JetBrains Mono', monospace;
            font-size: .72rem;
            background: rgba(255, 255, 255, .06);
            padding: 1px 5px;
            border-radius: 3px;
            color: var(--cyan)
        }

        footer {
            text-align: center;
            color: var(--text-dim);
            font-size: .72rem;
            letter-spacing: 1px;
            padding: 40px 0 20px;
            border-top: 1px solid var(--border);
            margin-top: 20px
        }
    </style>
</head>

<body>
    <div class="wrap">

        <!-- HEADER -->
        <header class="pg-header">
            <div class="badge">System Architecture Documentation</div>
            <h1>College Sports Management</h1>
            <p class="pg-sub">DFD Levels 0 · 1 · 2 &nbsp;·&nbsp; ER Diagram · Chen Notation &nbsp;·&nbsp; Deep Analysis</p>
        </header>


        <!-- ══════════════════════════════════
     DFD LEVEL 0
══════════════════════════════════ -->
        <section class="sec">
            <span class="sec-tag">DFD · Level 0</span>
            <h2 class="sec-title">Context Diagram</h2>
            <p class="sec-desc">System boundary — three external stakeholders and their bidirectional data flows with the central Sports Management process.</p>

            <div class="svgwrap">
                <svg viewBox="0 0 860 400" width="100%" style="min-width:560px">
                    <defs>
                        <marker id="ac0" markerWidth="9" markerHeight="7" refX="8" refY="3.5" orient="auto">
                            <polygon points="0 0,9 3.5,0 7" fill="#00d4ff" />
                        </marker>
                        <marker id="ap0" markerWidth="9" markerHeight="7" refX="8" refY="3.5" orient="auto">
                            <polygon points="0 0,9 3.5,0 7" fill="#b06cff" />
                        </marker>
                    </defs>

                    <!-- glow ring -->
                    <circle cx="430" cy="200" r="108" fill="none" stroke="rgba(176,108,255,.07)" stroke-width="1" stroke-dasharray="8 5" />

                    <!-- CENTRAL PROCESS -->
                    <circle cx="430" cy="200" r="88" fill="rgba(176,108,255,.1)" stroke="#b06cff" stroke-width="2" />
                    <text x="430" y="183" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#b06cff" letter-spacing="1">0.0</text>
                    <text x="430" y="199" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="12" fill="#c9d6e3" font-weight="700">COLLEGE SPORTS</text>
                    <text x="430" y="216" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="12" fill="#c9d6e3" font-weight="700">MANAGEMENT</text>
                    <text x="430" y="233" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#4a6278">SYSTEM</text>

                    <!-- ADMIN (left) center(90,200) -->
                    <rect x="20" y="172" width="140" height="56" rx="5" fill="rgba(0,212,255,.07)" stroke="#00d4ff" stroke-width="1.8" />
                    <text x="90" y="205" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="13" fill="#c9d6e3" font-weight="700">ADMIN</text>
                    <!-- Admin→System (top flow) -->
                    <line x1="160" y1="188" x2="342" y2="188" stroke="#00d4ff" stroke-width="1.8" stroke-opacity=".85" marker-end="url(#ac0)" />
                    <text x="251" y="181" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#00d4ff">Manage Users / Sports</text>
                    <!-- System→Admin (bottom flow) -->
                    <line x1="342" y1="212" x2="160" y2="212" stroke="#b06cff" stroke-width="1.6" stroke-opacity=".85" marker-end="url(#ap0)" />
                    <text x="251" y="225" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#b06cff">System Logs / Reports</text>

                    <!-- STAFF (top-right) center(720,80) -->
                    <rect x="648" y="52" width="144" height="56" rx="5" fill="rgba(0,212,255,.07)" stroke="#00d4ff" stroke-width="1.8" />
                    <text x="720" y="85" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="13" fill="#c9d6e3" font-weight="700">STAFF</text>
                    <!-- Staff→System: (650,72)→circle tangent
         vec from(430,200)→(720,80): dx=290,dy=-120,len=315 → tan=(430+88*290/315, 200+88*(-120)/315)=(511,167) -->
                    <line x1="648" y1="74" x2="511" y2="167" stroke="#00d4ff" stroke-width="1.8" stroke-opacity=".85" marker-end="url(#ac0)" />
                    <text x="568" y="108" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#00d4ff">Match Scoring</text>
                    <!-- System→Staff -->
                    <line x1="502" y1="156" x2="647" y2="67" stroke="#b06cff" stroke-width="1.6" stroke-opacity=".85" marker-end="url(#ap0)" />
                    <text x="590" y="130" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#b06cff">Schedule / Updates</text>

                    <!-- STUDENT (bottom-right) center(720,320) -->
                    <rect x="648" y="292" width="144" height="56" rx="5" fill="rgba(0,212,255,.07)" stroke="#00d4ff" stroke-width="1.8" />
                    <text x="720" y="325" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="13" fill="#c9d6e3" font-weight="700">STUDENT</text>
                    <!-- Student→System: vec(430,200)→(720,320): dx=290,dy=120,len=315 → tan=(430+88*290/315,200+88*120/315)=(511,233) -->
                    <line x1="648" y1="318" x2="511" y2="233" stroke="#00d4ff" stroke-width="1.8" stroke-opacity=".85" marker-end="url(#ac0)" />
                    <text x="568" y="296" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#00d4ff">Registration</text>
                    <!-- System→Student -->
                    <line x1="502" y1="244" x2="647" y2="325" stroke="#b06cff" stroke-width="1.6" stroke-opacity=".85" marker-end="url(#ap0)" />
                    <text x="590" y="302" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#b06cff">Certificates / Results</text>
                </svg>
            </div>

            <div class="legend">
                <div class="li">
                    <div class="ls" style="background:rgba(0,212,255,.07);border-color:#00d4ff"></div>External Entity
                </div>
                <div class="li">
                    <div class="ls" style="background:rgba(176,108,255,.1);border-color:#b06cff;border-radius:50%"></div>System Process
                </div>
                <div class="li">
                    <div style="width:26px;height:2px;background:#00d4ff"></div>Data Flow (inbound)
                </div>
                <div class="li">
                    <div style="width:26px;height:2px;background:#b06cff"></div>Data Flow (outbound)
                </div>
            </div>
        </section>


        <!-- ══════════════════════════════════
     DFD LEVEL 1
══════════════════════════════════ -->
        <section class="sec">
            <span class="sec-tag">DFD · Level 1</span>
            <h2 class="sec-title">System Overview — 5 Modules</h2>
            <p class="sec-desc">Five functional modules and their five data stores — each process writes to and reads from its dedicated MySQL table.</p>

            <div class="svgwrap">
                <svg viewBox="0 0 980 520" width="100%" style="min-width:720px">
                    <defs>
                        <marker id="L1c" markerWidth="9" markerHeight="7" refX="8" refY="3.5" orient="auto">
                            <polygon points="0 0,9 3.5,0 7" fill="#00d4ff" />
                        </marker>
                        <marker id="L1p" markerWidth="9" markerHeight="7" refX="8" refY="3.5" orient="auto">
                            <polygon points="0 0,9 3.5,0 7" fill="#b06cff" />
                        </marker>
                        <marker id="L1s" markerWidth="8" markerHeight="6" refX="7" refY="3" orient="auto">
                            <polygon points="0 0,8 3,0 6" fill="#4a6278" />
                        </marker>
                    </defs>

                    <!-- ─── ROW 1: 3 PROCESSES (cy=95) ─── -->
                    <!-- 1.0 User Auth -->
                    <circle cx="140" cy="95" r="54" fill="rgba(176,108,255,.1)" stroke="#b06cff" stroke-width="2" />
                    <text x="140" y="87" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#b06cff">1.0</text>
                    <text x="140" y="101" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">USER</text>
                    <text x="140" y="115" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">AUTH</text>

                    <!-- 2.0 Player Hub -->
                    <circle cx="490" cy="95" r="54" fill="rgba(176,108,255,.1)" stroke="#b06cff" stroke-width="2" />
                    <text x="490" y="87" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#b06cff">2.0</text>
                    <text x="490" y="101" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">PLAYER</text>
                    <text x="490" y="115" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">HUB</text>

                    <!-- 3.0 Sport Registry -->
                    <circle cx="840" cy="95" r="54" fill="rgba(176,108,255,.1)" stroke="#b06cff" stroke-width="2" />
                    <text x="840" y="87" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#b06cff">3.0</text>
                    <text x="840" y="101" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">SPORT</text>
                    <text x="840" y="115" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">REGISTRY</text>

                    <!-- ─── PROCESS-TO-PROCESS TRIGGERS (horizontal) ─── -->
                    <!-- 1.0→2.0: Validate Profile -->
                    <line x1="194" y1="95" x2="436" y2="95" stroke="#b06cff" stroke-width="1.8" stroke-dasharray="5,3" marker-end="url(#L1p)" />
                    <text x="315" y="86" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#b06cff">validate profile</text>
                    <!-- 2.0→3.0: Associate Sport -->
                    <line x1="544" y1="95" x2="786" y2="95" stroke="#4a6278" stroke-width="1.5" stroke-dasharray="4,3" marker-end="url(#L1s)" />
                    <text x="665" y="86" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#4a6278">associate sport</text>

                    <!-- ─── DATA STORES (y=230 band) ─── -->
                    <!-- D1: users (under 1.0) -->
                    <rect x="62" y="225" width="175" height="36" fill="rgba(0,212,255,.05)" stroke="#00d4ff" stroke-width="1.5" />
                    <line x1="62" y1="225" x2="62" y2="261" stroke="#00d4ff" stroke-width="3" />
                    <text x="150" y="248" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9.5" fill="#c9d6e3">D1: users</text>

                    <!-- D2: players (under 2.0) -->
                    <rect x="395" y="225" width="195" height="36" fill="rgba(0,212,255,.05)" stroke="#00d4ff" stroke-width="1.5" />
                    <line x1="395" y1="225" x2="395" y2="261" stroke="#00d4ff" stroke-width="3" />
                    <text x="492" y="248" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9.5" fill="#c9d6e3">D2: players</text>

                    <!-- D3: sports_categories (under 3.0) -->
                    <rect x="720" y="225" width="225" height="36" fill="rgba(0,212,255,.05)" stroke="#00d4ff" stroke-width="1.5" />
                    <line x1="720" y1="225" x2="720" y2="261" stroke="#00d4ff" stroke-width="3" />
                    <text x="832" y="248" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9.5" fill="#c9d6e3">D3: sports_categories</text>

                    <!-- D4: matches (mid row 2) -->
                    <rect x="310" y="350" width="175" height="36" fill="rgba(0,212,255,.05)" stroke="#00d4ff" stroke-width="1.5" />
                    <line x1="310" y1="350" x2="310" y2="386" stroke="#00d4ff" stroke-width="3" />
                    <text x="396" y="373" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9.5" fill="#c9d6e3">D4: matches</text>

                    <!-- D5: match_results (right row 2) -->
                    <rect x="620" y="350" width="210" height="36" fill="rgba(0,212,255,.05)" stroke="#00d4ff" stroke-width="1.5" />
                    <line x1="620" y1="350" x2="620" y2="386" stroke="#00d4ff" stroke-width="3" />
                    <text x="724" y="373" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9.5" fill="#c9d6e3">D5: match_results</text>

                    <!-- ─── PROCESS→STORE ARROWS ─── -->
                    <!-- 1.0↓D1 -->
                    <line x1="140" y1="149" x2="140" y2="225" stroke="#00d4ff" stroke-width="1.8" marker-end="url(#L1c)" />
                    <text x="148" y="192" font-family="'JetBrains Mono',monospace" font-size="7.5" fill="#00d4ff">user record</text>
                    <!-- 2.0↓D2 -->
                    <line x1="490" y1="149" x2="490" y2="225" stroke="#00d4ff" stroke-width="1.8" marker-end="url(#L1c)" />
                    <text x="498" y="192" font-family="'JetBrains Mono',monospace" font-size="7.5" fill="#00d4ff">player data</text>
                    <!-- 3.0↓D3 -->
                    <line x1="840" y1="149" x2="840" y2="225" stroke="#00d4ff" stroke-width="1.8" marker-end="url(#L1c)" />
                    <text x="848" y="192" font-family="'JetBrains Mono',monospace" font-size="7.5" fill="#00d4ff">sport config</text>

                    <!-- ─── ROW 2: PROCESSES (cy=450) ─── -->
                    <!-- 4.0 Match Master -->
                    <circle cx="395" cy="450" r="54" fill="rgba(176,108,255,.1)" stroke="#b06cff" stroke-width="2" />
                    <text x="395" y="442" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#b06cff">4.0</text>
                    <text x="395" y="456" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">MATCH</text>
                    <text x="395" y="470" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">MASTER</text>

                    <!-- 5.0 Scoring -->
                    <circle cx="725" cy="450" r="54" fill="rgba(176,108,255,.1)" stroke="#b06cff" stroke-width="2" />
                    <text x="725" y="442" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#b06cff">5.0</text>
                    <text x="725" y="456" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">SCORING</text>
                    <text x="725" y="470" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">ENGINE</text>

                    <!-- D2→4.0: pulls player data (dashed read) -->
                    <path d="M 492 261 Q 440 340 420 396" fill="none" stroke="#4a6278" stroke-width="1.5" stroke-dasharray="5,3" marker-end="url(#L1s)" />
                    <text x="430" y="322" font-family="'JetBrains Mono',monospace" font-size="7.5" fill="#4a6278">player info</text>
                    <!-- D3→4.0: pulls sport data (dashed read) -->
                    <path d="M 720 261 Q 560 320 445 396" fill="none" stroke="#4a6278" stroke-width="1.5" stroke-dasharray="4,3" marker-end="url(#L1s)" />
                    <text x="610" y="310" font-family="'JetBrains Mono',monospace" font-size="7.5" fill="#4a6278">sport config</text>
                    <!-- 4.0↓D4 -->
                    <line x1="395" y1="396" x2="395" y2="350" stroke="#00d4ff" stroke-width="1.8" marker-end="url(#L1c)" />
                    <text x="403" y="376" font-family="'JetBrains Mono',monospace" font-size="7.5" fill="#00d4ff">match record</text>
                    <!-- 4.0→5.0 -->
                    <line x1="449" y1="450" x2="671" y2="450" stroke="#b06cff" stroke-width="1.8" stroke-dasharray="5,3" marker-end="url(#L1p)" />
                    <text x="560" y="441" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#b06cff">active match</text>
                    <!-- 5.0↓D5 -->
                    <line x1="725" y1="396" x2="725" y2="386" stroke="#00d4ff" stroke-width="1.8" marker-end="url(#L1c)" />
                    <text x="733" y="374" font-family="'JetBrains Mono',monospace" font-size="7.5" fill="#00d4ff">results write</text>
                    <!-- D4→5.0 read match (dashed) -->
                    <path d="M 485 368 Q 600 380 671 440" fill="none" stroke="#4a6278" stroke-width="1.4" stroke-dasharray="4,3" marker-end="url(#L1s)" />
                    <text x="580" y="370" font-family="'JetBrains Mono',monospace" font-size="7.5" fill="#4a6278">match ref</text>
                </svg>
            </div>

            <div class="legend">
                <div class="li">
                    <div style="width:26px;height:2px;background:#00d4ff"></div>Write / Store
                </div>
                <div class="li">
                    <div style="width:26px;height:2px;background:#b06cff"></div>Process Trigger
                </div>
                <div class="li">
                    <div style="width:26px;border-top:2px dashed #4a6278"></div>Read / Query
                </div>
                <div class="li">
                    <div style="width:22px;height:13px;border-right:1.5px solid #00d4ff;border-top:1.5px solid #00d4ff;border-bottom:1.5px solid #00d4ff"></div>Data Store
                </div>
            </div>
        </section>


        <!-- ══════════════════════════════════
     DFD LEVEL 2
══════════════════════════════════ -->
        <section class="sec">
            <span class="sec-tag">DFD · Level 2</span>
            <h2 class="sec-title">Match Lifecycle — Process 4.0 Detail</h2>
            <p class="sec-desc">Six sub-processes showing how a match moves from scheduling through team allocation, live scoring, result generation, and statistics update — forming a complete cycle.</p>

            <div class="svgwrap">
                <svg viewBox="0 0 980 400" width="100%" style="min-width:700px">
                    <defs>
                        <marker id="L2c" markerWidth="9" markerHeight="7" refX="8" refY="3.5" orient="auto">
                            <polygon points="0 0,9 3.5,0 7" fill="#00d4ff" />
                        </marker>
                        <marker id="L2p" markerWidth="9" markerHeight="7" refX="8" refY="3.5" orient="auto">
                            <polygon points="0 0,9 3.5,0 7" fill="#b06cff" />
                        </marker>
                        <marker id="L2o" markerWidth="9" markerHeight="7" refX="8" refY="3.5" orient="auto">
                            <polygon points="0 0,9 3.5,0 7" fill="#ff9100" />
                        </marker>
                        <marker id="L2s" markerWidth="8" markerHeight="6" refX="7" refY="3" orient="auto">
                            <polygon points="0 0,8 3,0 6" fill="#4a6278" />
                        </marker>
                    </defs>

                    <!-- ─── TOP ROW: 4.1→4.3 (cy=100) cx=120,370,640 ─── -->
                    <circle cx="120" cy="100" r="52" fill="rgba(176,108,255,.1)" stroke="#b06cff" stroke-width="2" />
                    <text x="120" y="92" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#b06cff">4.1</text>
                    <text x="120" y="106" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">SCHEDULE</text>
                    <text x="120" y="120" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">MATCH</text>

                    <circle cx="370" cy="100" r="52" fill="rgba(176,108,255,.1)" stroke="#b06cff" stroke-width="2" />
                    <text x="370" y="92" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#b06cff">4.2</text>
                    <text x="370" y="106" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">TEAM</text>
                    <text x="370" y="120" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">ALLOCATION</text>

                    <circle cx="640" cy="100" r="52" fill="rgba(176,108,255,.1)" stroke="#b06cff" stroke-width="2" />
                    <text x="640" y="92" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#b06cff">4.3</text>
                    <text x="640" y="106" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">MATCH</text>
                    <text x="640" y="120" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">SETUP</text>

                    <!-- TOP ROW ARROWS -->
                    <!-- 4.1→4.2 -->
                    <line x1="172" y1="100" x2="318" y2="100" stroke="#00d4ff" stroke-width="2" marker-end="url(#L2c)" />
                    <text x="245" y="91" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#00d4ff">date / venue</text>
                    <!-- 4.2→4.3 -->
                    <line x1="422" y1="100" x2="588" y2="100" stroke="#00d4ff" stroke-width="2" marker-end="url(#L2c)" />
                    <text x="505" y="91" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#00d4ff">T1 vs T2</text>

                    <!-- 4.3 DOWN to 4.4 -->
                    <line x1="640" y1="152" x2="640" y2="248" stroke="#ff9100" stroke-width="2" marker-end="url(#L2o)" />
                    <text x="655" y="205" font-family="'JetBrains Mono',monospace" font-size="8" fill="#ff9100">active match</text>

                    <!-- ─── BOTTOM ROW: 4.6→4.4 (cy=300) cx=120,370,640 ─── -->
                    <circle cx="640" cy="300" r="52" fill="rgba(176,108,255,.1)" stroke="#b06cff" stroke-width="2" />
                    <text x="640" y="292" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#b06cff">4.4</text>
                    <text x="640" y="306" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">SCORE</text>
                    <text x="640" y="320" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">ENTRY</text>

                    <circle cx="370" cy="300" r="52" fill="rgba(176,108,255,.1)" stroke="#b06cff" stroke-width="2" />
                    <text x="370" y="292" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#b06cff">4.5</text>
                    <text x="370" y="306" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">RESULT</text>
                    <text x="370" y="320" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">GENERATION</text>

                    <circle cx="120" cy="300" r="52" fill="rgba(176,108,255,.1)" stroke="#b06cff" stroke-width="2" />
                    <text x="120" y="292" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#b06cff">4.6</text>
                    <text x="120" y="306" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">STAT</text>
                    <text x="120" y="320" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="10.5" fill="#c9d6e3" font-weight="600">UPDATE</text>

                    <!-- BOTTOM ROW ARROWS (right to left) -->
                    <!-- 4.4→4.5 -->
                    <line x1="588" y1="300" x2="422" y2="300" stroke="#b06cff" stroke-width="2" marker-end="url(#L2p)" />
                    <text x="505" y="291" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#b06cff">submit scores</text>
                    <!-- 4.5→4.6 -->
                    <line x1="318" y1="300" x2="172" y2="300" stroke="#b06cff" stroke-width="2" marker-end="url(#L2p)" />
                    <text x="245" y="291" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#b06cff">winner record</text>
                    <!-- 4.6 UP back to 4.1 (cycle close) -->
                    <path d="M 68 300 Q 30 300 30 100 Q 30 100 68 100" fill="none" stroke="#4a6278" stroke-width="1.5" stroke-dasharray="5,3" marker-end="url(#L2s)" />
                    <text x="14" y="205" font-family="'JetBrains Mono',monospace" font-size="8" fill="#4a6278" transform="rotate(-90 14 205)">perf. logs</text>

                    <!-- DATA STORES -->
                    <!-- D4 under top row -->
                    <rect x="800" y="65" width="165" height="34" fill="rgba(0,212,255,.05)" stroke="#00d4ff" stroke-width="1.5" />
                    <line x1="800" y1="65" x2="800" y2="99" stroke="#00d4ff" stroke-width="3" />
                    <text x="882" y="87" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9.5" fill="#c9d6e3">D4: matches</text>
                    <!-- D5 under bottom row -->
                    <rect x="800" y="265" width="165" height="34" fill="rgba(0,212,255,.05)" stroke="#00d4ff" stroke-width="1.5" />
                    <line x1="800" y1="265" x2="800" y2="299" stroke="#00d4ff" stroke-width="3" />
                    <text x="882" y="287" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9.5" fill="#c9d6e3">D5: match_results</text>

                    <!-- 4.3→D4 write -->
                    <line x1="692" y1="100" x2="800" y2="82" stroke="#00d4ff" stroke-width="1.8" marker-end="url(#L2c)" />
                    <text x="752" y="82" font-family="'JetBrains Mono',monospace" font-size="7.5" fill="#00d4ff">write match</text>
                    <!-- 4.4→D5 write -->
                    <line x1="692" y1="300" x2="800" y2="282" stroke="#00d4ff" stroke-width="1.8" marker-end="url(#L2c)" />
                    <text x="748" y="282" font-family="'JetBrains Mono',monospace" font-size="7.5" fill="#00d4ff">write result</text>
                    <!-- D4→4.4 read (dashed) -->
                    <path d="M 800 99 Q 760 200 692 282" fill="none" stroke="#4a6278" stroke-width="1.4" stroke-dasharray="4,3" marker-end="url(#L2s)" />
                    <text x="782" y="200" font-family="'JetBrains Mono',monospace" font-size="7.5" fill="#4a6278">read ref</text>
                </svg>
            </div>

            <div class="legend">
                <div class="li">
                    <div style="width:26px;height:2px;background:#00d4ff"></div>Write / Primary
                </div>
                <div class="li">
                    <div style="width:26px;height:2px;background:#b06cff"></div>Process Event
                </div>
                <div class="li">
                    <div style="width:26px;height:2px;background:#ff9100"></div>State Transition
                </div>
                <div class="li">
                    <div style="width:26px;border-top:2px dashed #4a6278"></div>Read / Cycle
                </div>
            </div>
        </section>


        <!-- ══════════════════════════════════
     ER DIAGRAM — CHEN NOTATION
══════════════════════════════════ -->
        <section class="sec">
            <span class="sec-tag">ER Diagram · Chen Notation</span>
            <h2 class="sec-title">Entity Relationship Diagram</h2>
            <p class="sec-desc">Full logical schema — strong entities, weak entity (double rectangle), identifying relationship (double diamond), derived attributes (dashed ovals), primary key attributes (underlined), and cardinality on every line.</p>

            <div class="legend" style="margin-bottom:20px">
                <div class="li">
                    <div class="ls" style="background:rgba(0,212,255,.07);border-color:#00d4ff"></div>Strong Entity
                </div>
                <div class="li">
                    <div style="display:flex;align-items:center;gap:1px">
                        <div style="width:16px;height:16px;border:2px solid #00d4ff;border-radius:2px"></div>
                        <div style="width:13px;height:13px;border:1px solid #00d4ff;border-radius:1px;margin-left:-12px"></div>
                    </div>Weak Entity
                </div>
                <div class="li">
                    <div style="width:20px;height:11px;border:1.5px solid #4a6278;border-radius:50%"></div>Attribute
                </div>
                <div class="li">
                    <div style="width:20px;height:11px;border:1.5px dashed #ffc400;border-radius:50%"></div>Derived Attr
                </div>
                <div class="li">
                    <svg width="22" height="16">
                        <polygon points="11,1 21,8 11,15 1,8" fill="rgba(176,108,255,.12)" stroke="#b06cff" stroke-width="1.5" />
                    </svg>
                    Relationship
                </div>
                <div class="li">
                    <svg width="24" height="18">
                        <polygon points="12,1 23,9 12,17 1,9" fill="none" stroke="#b06cff" stroke-width="2" />
                        <polygon points="12,4 20,9 12,14 4,9" fill="rgba(176,108,255,.1)" stroke="#b06cff" stroke-width="1" />
                    </svg>
                    Identifying Rel.
                </div>
            </div>

            <div class="svgwrap">
                <svg viewBox="0 0 1160 720" width="100%" style="min-width:800px">
                    <defs>
                        <marker id="E1" markerWidth="7" markerHeight="5" refX="6" refY="2.5" orient="auto">
                            <polygon points="0 0,7 2.5,0 5" fill="#4a6278" />
                        </marker>
                    </defs>

                    <!-- ═══════════════════════════════════════════
         ROW 1:  USERS ──registers── PLAYERS ──bridge── SPORTS_CATEGORIES
    ═══════════════════════════════════════════ -->

                    <!-- USERS entity: center(130,200) -->
                    <rect x="65" y="175" width="130" height="50" rx="4" fill="rgba(0,212,255,.07)" stroke="#00d4ff" stroke-width="2" />
                    <text x="130" y="205" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="13" fill="#c9d6e3" font-weight="700">USERS</text>
                    <!-- user_id key: above -->
                    <ellipse cx="130" cy="120" rx="40" ry="17" fill="rgba(0,212,255,.06)" stroke="#00d4ff" stroke-width="1.5" />
                    <text x="130" y="118" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#00d4ff" text-decoration="underline">user_id</text>
                    <line x1="130" y1="137" x2="130" y2="175" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />
                    <!-- username: above-left -->
                    <ellipse cx="50" cy="135" rx="38" ry="16" fill="rgba(255,255,255,.03)" stroke="#4a6278" stroke-width="1.5" />
                    <text x="50" y="139" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#8899a8">username</text>
                    <line x1="85" y1="175" x2="72" y2="151" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />
                    <!-- role: below-left -->
                    <ellipse cx="60" cy="270" rx="32" ry="16" fill="rgba(255,255,255,.03)" stroke="#4a6278" stroke-width="1.5" />
                    <text x="60" y="274" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#8899a8">role</text>
                    <line x1="85" y1="225" x2="76" y2="254" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />

                    <!-- REGISTERS diamond: center(295,200) -->
                    <polygon points="295,175 330,200 295,225 260,200" fill="rgba(176,108,255,.12)" stroke="#b06cff" stroke-width="2" />
                    <text x="295" y="196" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#b06cff">regis-</text>
                    <text x="295" y="208" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#b06cff">ters</text>
                    <!-- USERS right(195,200) → diamond left(260,200) -->
                    <line x1="195" y1="200" x2="260" y2="200" stroke="#4a6278" stroke-width="1.5" />
                    <text x="213" y="193" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#4a6278">1</text>
                    <!-- diamond right(330,200) → PLAYERS left(410,200) -->
                    <line x1="330" y1="200" x2="410" y2="200" stroke="#4a6278" stroke-width="1.5" marker-end="url(#E1)" />
                    <text x="393" y="193" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#4a6278">M</text>

                    <!-- PLAYERS entity: center(490,200) -->
                    <rect x="410" y="175" width="160" height="50" rx="4" fill="rgba(0,212,255,.07)" stroke="#00d4ff" stroke-width="2" />
                    <text x="490" y="205" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="13" fill="#c9d6e3" font-weight="700">PLAYERS</text>
                    <!-- player_id key: above-center -->
                    <ellipse cx="490" cy="110" rx="40" ry="17" fill="rgba(0,212,255,.06)" stroke="#00d4ff" stroke-width="1.5" />
                    <text x="490" y="108" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#00d4ff" text-decoration="underline">player_id</text>
                    <line x1="490" y1="127" x2="490" y2="175" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />
                    <!-- register_number key: above-left -->
                    <ellipse cx="360" cy="120" rx="56" ry="17" fill="rgba(0,212,255,.06)" stroke="#00d4ff" stroke-width="1.5" />
                    <text x="360" y="118" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#00d4ff" text-decoration="underline">reg_number</text>
                    <line x1="420" y1="175" x2="388" y2="137" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />
                    <!-- age DERIVED: above-right (dashed) -->
                    <ellipse cx="620" cy="120" rx="32" ry="17" fill="rgba(255,255,255,.02)" stroke="#ffc400" stroke-width="1.5" stroke-dasharray="5,3" />
                    <text x="620" y="124" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#ffc400">age</text>
                    <line x1="560" y1="175" x2="604" y2="137" stroke="#4a6278" stroke-width="1.2" stroke-dasharray="4,2" marker-end="url(#E1)" />
                    <!-- name: below-right -->
                    <ellipse cx="560" cy="275" rx="36" ry="17" fill="rgba(255,255,255,.03)" stroke="#4a6278" stroke-width="1.5" />
                    <text x="560" y="279" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#8899a8">name</text>
                    <line x1="540" y1="225" x2="548" y2="258" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />

                    <!-- BRIDGE (player_sports) diamond: center(720,200) -->
                    <polygon points="720,175 755,200 720,225 685,200" fill="rgba(176,108,255,.12)" stroke="#b06cff" stroke-width="2" />
                    <text x="720" y="196" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#b06cff">plays</text>
                    <text x="720" y="208" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8" fill="#4a6278">(bridge)</text>
                    <!-- PLAYERS right(570,200) → bridge left(685,200) -->
                    <line x1="570" y1="200" x2="685" y2="200" stroke="#4a6278" stroke-width="1.5" />
                    <text x="590" y="193" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#4a6278">M</text>
                    <!-- bridge right(755,200) → SPORTS left(820,200) -->
                    <line x1="755" y1="200" x2="820" y2="200" stroke="#4a6278" stroke-width="1.5" marker-end="url(#E1)" />
                    <text x="802" y="193" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#4a6278">N</text>

                    <!-- SPORTS_CATEGORIES entity: center(940,200) -->
                    <rect x="820" y="175" width="240" height="50" rx="4" fill="rgba(0,212,255,.07)" stroke="#00d4ff" stroke-width="2" />
                    <text x="940" y="205" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="12" fill="#c9d6e3" font-weight="700">SPORTS_CATEGORIES</text>
                    <!-- sport_id key: above-left -->
                    <ellipse cx="870" cy="120" rx="40" ry="17" fill="rgba(0,212,255,.06)" stroke="#00d4ff" stroke-width="1.5" />
                    <text x="870" y="118" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#00d4ff" text-decoration="underline">sport_id</text>
                    <line x1="870" y1="137" x2="870" y2="175" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />
                    <!-- sport_name: above-right -->
                    <ellipse cx="1020" cy="120" rx="52" ry="17" fill="rgba(255,255,255,.03)" stroke="#4a6278" stroke-width="1.5" />
                    <text x="1020" y="124" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#8899a8">sport_name</text>
                    <line x1="1010" y1="137" x2="1000" y2="175" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />
                    <!-- max_players: below -->
                    <ellipse cx="940" cy="275" rx="48" ry="17" fill="rgba(255,255,255,.03)" stroke="#4a6278" stroke-width="1.5" />
                    <text x="940" y="279" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#8899a8">max_players</text>
                    <line x1="940" y1="225" x2="940" y2="258" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />

                    <!-- ═══════════════════════════════════════════
         ROW 2: SPORTS_CATEGORIES──forms──TEAMS
                TEAMS──plays──MATCHES
    ═══════════════════════════════════════════ -->

                    <!-- FORMS diamond: center(940,390) -->
                    <polygon points="940,362 975,390 940,418 905,390" fill="rgba(176,108,255,.12)" stroke="#b06cff" stroke-width="2" />
                    <text x="940" y="394" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#b06cff">forms</text>
                    <!-- SPORTS bottom(940,225) → FORMS top(940,362) -->
                    <line x1="940" y1="225" x2="940" y2="362" stroke="#4a6278" stroke-width="1.5" />
                    <text x="948" y="295" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#4a6278">1</text>
                    <!-- FORMS bottom(940,418) → TEAMS top -->
                    <line x1="940" y1="418" x2="940" y2="465" stroke="#4a6278" stroke-width="1.5" marker-end="url(#E1)" />
                    <text x="948" y="450" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#4a6278">M</text>

                    <!-- TEAMS entity: center(940,500) -->
                    <rect x="865" y="480" width="150" height="50" rx="4" fill="rgba(0,212,255,.07)" stroke="#00d4ff" stroke-width="2" />
                    <text x="940" y="510" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="13" fill="#c9d6e3" font-weight="700">TEAMS</text>
                    <!-- team_id key: above-left -->
                    <ellipse cx="820" cy="450" rx="36" ry="17" fill="rgba(0,212,255,.06)" stroke="#00d4ff" stroke-width="1.5" />
                    <text x="820" y="448" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#00d4ff" text-decoration="underline">team_id</text>
                    <line x1="840" y1="467" x2="872" y2="480" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />
                    <!-- team_name: below -->
                    <ellipse cx="940" cy="580" rx="48" ry="17" fill="rgba(255,255,255,.03)" stroke="#4a6278" stroke-width="1.5" />
                    <text x="940" y="584" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#8899a8">team_name</text>
                    <line x1="940" y1="530" x2="940" y2="563" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />

                    <!-- PLAYS (TEAMS vs MATCHES) diamond: center(680,500) -->
                    <polygon points="680,474 715,500 680,526 645,500" fill="rgba(176,108,255,.12)" stroke="#b06cff" stroke-width="2" />
                    <text x="680" y="504" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#b06cff">plays</text>
                    <!-- TEAMS left(865,500) → PLAYS right(715,500) -->
                    <line x1="865" y1="500" x2="715" y2="500" stroke="#4a6278" stroke-width="1.5" />
                    <text x="848" y="493" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#4a6278">M</text>
                    <!-- PLAYS left(645,500) → MATCHES right -->
                    <line x1="645" y1="500" x2="590" y2="500" stroke="#4a6278" stroke-width="1.5" marker-end="url(#E1)" />
                    <text x="626" y="493" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#4a6278">N</text>

                    <!-- MATCHES entity: center(490,500) -->
                    <rect x="410" y="475" width="180" height="50" rx="4" fill="rgba(0,212,255,.07)" stroke="#00d4ff" stroke-width="2" />
                    <text x="500" y="505" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="13" fill="#c9d6e3" font-weight="700">MATCHES</text>
                    <!-- match_id key: above -->
                    <ellipse cx="490" cy="430" rx="40" ry="17" fill="rgba(0,212,255,.06)" stroke="#00d4ff" stroke-width="1.5" />
                    <text x="490" y="428" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#00d4ff" text-decoration="underline">match_id</text>
                    <line x1="490" y1="447" x2="490" y2="475" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />
                    <!-- match_date: below-left -->
                    <ellipse cx="420" cy="575" rx="46" ry="17" fill="rgba(255,255,255,.03)" stroke="#4a6278" stroke-width="1.5" />
                    <text x="420" y="579" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#8899a8">match_date</text>
                    <line x1="436" y1="525" x2="428" y2="558" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />
                    <!-- venue: below-right -->
                    <ellipse cx="570" cy="575" rx="36" ry="17" fill="rgba(255,255,255,.03)" stroke="#4a6278" stroke-width="1.5" />
                    <text x="570" y="579" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#8899a8">venue</text>
                    <line x1="554" y1="525" x2="562" y2="558" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />

                    <!-- ═══════════════════════════════════════════
         MATCHES──yields(ident)──MATCH_RESULTS(weak)
    ═══════════════════════════════════════════ -->

                    <!-- YIELDS (identifying double-diamond): center(280,500) -->
                    <polygon points="280,472 318,500 280,528 242,500" fill="rgba(176,108,255,.12)" stroke="#b06cff" stroke-width="2.5" />
                    <polygon points="280,477 312,500 280,523 248,500" fill="none" stroke="#b06cff" stroke-width="1" />
                    <text x="280" y="504" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#b06cff">yields</text>
                    <!-- MATCHES left(410,500) → YIELDS right(318,500) -->
                    <line x1="410" y1="500" x2="318" y2="500" stroke="#4a6278" stroke-width="1.5" />
                    <text x="395" y="493" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#4a6278">1</text>
                    <!-- YIELDS left(242,500) → MATCH_RESULTS right -->
                    <line x1="242" y1="500" x2="195" y2="500" stroke="#4a6278" stroke-width="1.5" marker-end="url(#E1)" />
                    <text x="227" y="493" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#4a6278">1</text>

                    <!-- MATCH_RESULTS weak entity: center(130,500) -->
                    <rect x="50" y="475" width="145" height="50" rx="4" fill="rgba(0,212,255,.07)" stroke="#00d4ff" stroke-width="3" />
                    <rect x="56" y="481" width="133" height="38" rx="2" fill="none" stroke="#00d4ff" stroke-width="1" />
                    <text x="122" y="505" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="11" fill="#c9d6e3" font-weight="700">MATCH_RESULTS</text>
                    <!-- result_id key: above -->
                    <ellipse cx="122" cy="430" rx="40" ry="17" fill="rgba(0,212,255,.06)" stroke="#00d4ff" stroke-width="1.5" />
                    <text x="122" y="428" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#00d4ff" text-decoration="underline">result_id</text>
                    <line x1="122" y1="447" x2="122" y2="475" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />
                    <!-- winner_team_id: below-left -->
                    <ellipse cx="50" cy="590" rx="54" ry="17" fill="rgba(255,255,255,.03)" stroke="#4a6278" stroke-width="1.5" />
                    <text x="50" y="594" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#8899a8">winner_team_id</text>
                    <line x1="72" y1="525" x2="58" y2="573" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />
                    <!-- score: below-right -->
                    <ellipse cx="190" cy="590" rx="34" ry="17" fill="rgba(255,255,255,.03)" stroke="#4a6278" stroke-width="1.5" />
                    <text x="190" y="594" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#8899a8">score</text>
                    <line x1="170" y1="525" x2="182" y2="573" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />

                    <!-- ═══════════════════════════════════════════
         USERS ── triggers ── AUDIT_LOG (bottom left)
    ═══════════════════════════════════════════ -->

                    <!-- TRIGGERS diamond: center(130,380) -->
                    <polygon points="130,358 160,380 130,402 100,380" fill="rgba(176,108,255,.12)" stroke="#b06cff" stroke-width="2" />
                    <text x="130" y="384" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#b06cff">logs</text>
                    <!-- USERS bottom(130,225) → TRIGGERS top(130,358) -->
                    <line x1="130" y1="225" x2="130" y2="358" stroke="#4a6278" stroke-width="1.5" />
                    <text x="138" y="295" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#4a6278">1</text>
                    <!-- TRIGGERS bottom(130,402) → AUDIT_LOG top -->
                    <line x1="130" y1="402" x2="130" y2="460" stroke="#4a6278" stroke-width="1.5" marker-end="url(#E1)" />
                    <text x="138" y="435" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#4a6278">N</text>

                    <!-- AUDIT_LOG entity: center(130,497) — above MATCH_RESULTS row, use a shifted position -->
                    <!-- Actually place AUDIT_LOG on left side, offset: center(130,497 conflicts — use cy=310 region -->
                    <!-- AUDIT_LOG: center(280,380) -->
                    <!-- Redo: triggers diamond at (130,330), audit_log at (280,330) -->
                    <!-- Let me place TRIGGERS properly at (200,340) and AUDIT_LOG at (330,340) -->

                    <!-- TRIGGERS diamond: center(200,340) (redrawn cleanly) -->
                    <polygon points="200,316 232,340 200,364 168,340" fill="rgba(176,108,255,.12)" stroke="#b06cff" stroke-width="2" />
                    <text x="200" y="344" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#b06cff">logs</text>
                    <!-- USERS bottom path down and right to diamond -->
                    <path d="M 170 225 Q 170 340 168 340" fill="none" stroke="#4a6278" stroke-width="1.5" />
                    <text x="155" y="285" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#4a6278">1</text>
                    <!-- diamond → AUDIT_LOG right(232,340)→(300,340) -->
                    <line x1="232" y1="340" x2="298" y2="340" stroke="#4a6278" stroke-width="1.5" marker-end="url(#E1)" />
                    <text x="252" y="332" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#4a6278">N</text>

                    <!-- AUDIT_LOG entity: center(370,340) -->
                    <rect x="298" y="315" width="144" height="50" rx="4" fill="rgba(0,212,255,.07)" stroke="#00d4ff" stroke-width="2" />
                    <text x="370" y="345" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="12" fill="#c9d6e3" font-weight="700">AUDIT_LOG</text>
                    <!-- log_id key: above -->
                    <ellipse cx="370" cy="280" rx="36" ry="15" fill="rgba(0,212,255,.06)" stroke="#00d4ff" stroke-width="1.5" />
                    <text x="370" y="278" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#00d4ff" text-decoration="underline">log_id</text>
                    <line x1="370" y1="295" x2="370" y2="315" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />
                    <!-- action: below-left -->
                    <ellipse cx="315" cy="400" rx="32" ry="15" fill="rgba(255,255,255,.03)" stroke="#4a6278" stroke-width="1.5" />
                    <text x="315" y="404" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#8899a8">action</text>
                    <line x1="332" y1="365" x2="322" y2="385" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />
                    <!-- ip_address: below-right -->
                    <ellipse cx="435" cy="400" rx="44" ry="15" fill="rgba(255,255,255,.03)" stroke="#4a6278" stroke-width="1.5" />
                    <text x="435" y="404" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#8899a8">ip_address</text>
                    <line x1="410" y1="365" x2="422" y2="385" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />

                    <!-- CERTIFICATE entity bottom-right -->
                    <!-- GENERATES diamond at (700,640), CERTIFICATE at (840,640) -->
                    <polygon points="700,615 732,640 700,665 668,640" fill="rgba(176,108,255,.12)" stroke="#b06cff" stroke-width="2" />
                    <text x="700" y="644" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#b06cff">generates</text>
                    <!-- MATCH_RESULTS bottom path → GENERATES -->
                    <path d="M 122 525 Q 122 640 668 640" fill="none" stroke="#4a6278" stroke-width="1.5" stroke-dasharray="5,3" />
                    <text x="400" y="655" font-family="'JetBrains Mono',monospace" font-size="8" fill="#4a6278">1</text>
                    <!-- GENERATES → CERTIFICATE -->
                    <line x1="732" y1="640" x2="808" y2="640" stroke="#4a6278" stroke-width="1.5" marker-end="url(#E1)" />
                    <text x="762" y="632" font-family="'JetBrains Mono',monospace" font-size="8.5" fill="#4a6278">N</text>

                    <!-- CERTIFICATE entity: center(890,640) -->
                    <rect x="808" y="615" width="165" height="50" rx="4" fill="rgba(0,212,255,.07)" stroke="#00d4ff" stroke-width="2" />
                    <text x="890" y="645" text-anchor="middle" font-family="'Outfit',sans-serif" font-size="13" fill="#c9d6e3" font-weight="700">CERTIFICATES</text>
                    <!-- cert_id key: above -->
                    <ellipse cx="890" cy="580" rx="38" ry="15" fill="rgba(0,212,255,.06)" stroke="#00d4ff" stroke-width="1.5" />
                    <text x="890" y="578" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#00d4ff" text-decoration="underline">cert_id</text>
                    <line x1="890" y1="595" x2="890" y2="615" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />
                    <!-- issued_date: below -->
                    <ellipse cx="890" cy="705" rx="50" ry="16" fill="rgba(255,255,255,.03)" stroke="#4a6278" stroke-width="1.5" />
                    <text x="890" y="709" text-anchor="middle" font-family="'JetBrains Mono',monospace" font-size="9" fill="#8899a8">issued_date</text>
                    <line x1="890" y1="665" x2="890" y2="689" stroke="#4a6278" stroke-width="1.2" marker-end="url(#E1)" />
                </svg>
            </div>
        </section>


        <!-- ══════════════════════════════════
     ANALYSIS
══════════════════════════════════ -->
        <section class="sec" style="padding-bottom:36px">
            <span class="sec-tag">Architecture</span>
            <h2 class="sec-title">Deep System Analysis</h2>
            <p class="sec-desc">Technical documentation of design patterns, database logic, security, and scalability decisions.</p>

            <div class="agrid">
                <div class="acard c1">
                    <h4>📊 Data Flow Logic</h4>
                    <p>The system uses a <strong style="color:#c9d6e3">Push-Pull Architecture</strong>. Student registration data is pushed from the registration module to the Player Hub, while the Match Scheduling engine pulls availability from both the Sports Registry and Team containers to prevent venue and time conflicts. The Scoring Engine fires as a downstream event only after Match Setup is confirmed in <code>D4: matches</code>, ensuring no orphan score records.</p>
                </div>
                <div class="acard c2">
                    <h4>🔗 ER Relationship Analysis</h4>
                    <p>Features a complex <strong style="color:#c9d6e3">M:N recursive relationship</strong> between Players and Sports resolved via the <code>player_sports</code> bridge table. <code>MATCH_RESULTS</code> is modeled as a <strong>Weak Entity</strong> with an identifying relationship to <code>MATCHES</code> — it cannot exist without a parent match record (ON DELETE CASCADE). Cardinality is enforced at the DB level via foreign key constraints throughout.</p>
                </div>
                <div class="acard c3">
                    <h4>🛡️ Security Architecture</h4>
                    <p>Implements <strong style="color:#c9d6e3">Layered RBAC</strong> through the Auth module. Credentials persisted with BCrypt hashing. All state-changing transactions (score updates, result finalization) are intercepted by the Audit Logging process which records <code>ip_address</code>, <code>user_id</code>, and <code>action</code> type — providing full forensic traceability for institutional compliance.</p>
                </div>
                <div class="acard c4">
                    <h4>📈 Scalability Patterns</h4>
                    <p>Database normalized to <strong style="color:#c9d6e3">3NF</strong>, eliminating transitive dependencies. The Sports Registry supports 100+ disciplines via indexed lookups on <code>sport_id</code>. Certificate generation is decoupled from the results pipeline — issued asynchronously post-result, enabling the system to scale horizontally by sport category without restructuring the core match lifecycle tables.</p>
                </div>
            </div>
        </section>

        <footer>
            &copy; 2025 iBOY Innovation HUB &nbsp;·&nbsp; Developed by Jaiganesh D. (iBOY) &nbsp;·&nbsp; Technical Architecture Documentation
        </footer>

    </div>
</body>

</html>