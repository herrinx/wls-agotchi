<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WLS-agotchi - We Like Shooting Virtual Pets</title>
    <style>
        :root {
            --lcd-bg: #1a1a2e;
            --lcd-dark: #0f0f1a;
            --lcd-light: #2d2d44;
            --neon-pink: #ff6b9d;
            --neon-blue: #4ecdc4;
            --neon-green: #95e1d3;
            --neon-red: #ff6b6b;
            --neon-yellow: #ffd93d;
            --pixel-border: #3d3d5c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            image-rendering: pixelated;
            image-rendering: -moz-crisp-edges;
            image-rendering: crisp-edges;
        }

        body {
            font-family: 'Courier New', monospace;
            background: linear-gradient(135deg, var(--lcd-dark) 0%, var(--lcd-bg) 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #fff;
        }

        .device {
            background: linear-gradient(145deg, #2a2a3e 0%, #1a1a2e 100%);
            border-radius: 30px;
            padding: 30px;
            box-shadow: 
                0 20px 60px rgba(0,0,0,0.5),
                inset 0 2px 0 rgba(255,255,255,0.1);
            max-width: 400px;
            width: 100%;
        }

        .device-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .device-header h1 {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--neon-pink);
            text-shadow: 0 0 10px var(--neon-pink);
        }

        .screen {
            background: var(--lcd-dark);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 
                inset 0 4px 20px rgba(0,0,0,0.5),
                0 1px 0 rgba(255,255,255,0.1);
            min-height: 320px;
            position: relative;
            overflow: hidden;
        }

        .screen::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: repeating-linear-gradient(
                0deg,
                transparent,
                transparent 2px,
                rgba(0,0,0,0.1) 2px,
                rgba(0,0,0,0.1) 4px
            );
            pointer-events: none;
            opacity: 0.3;
        }

        .character-display {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 200px;
        }

        .pixel-art {
            width: 120px;
            height: 120px;
            margin-bottom: 15px;
            filter: drop-shadow(0 0 20px rgba(78, 205, 196, 0.3));
        }

        .pixel-art svg {
            width: 100%;
            height: 100%;
        }

        .character-name {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .character-status {
            font-size: 12px;
            opacity: 0.8;
            text-transform: uppercase;
        }

        .stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 15px;
        }

        .stat {
            background: var(--lcd-light);
            border-radius: 8px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .stat-label {
            font-size: 10px;
            text-transform: uppercase;
            opacity: 0.7;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .stat-bar {
            height: 8px;
            background: var(--lcd-dark);
            border-radius: 4px;
            overflow: hidden;
        }

        .stat-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        .stat-hunger .stat-fill { background: var(--neon-yellow); }
        .stat-happiness .stat-fill { background: var(--neon-pink); }
        .stat-energy .stat-fill { background: var(--neon-blue); }
        .stat-health .stat-fill { background: var(--neon-green); }
        .stat-fill.critical { background: var(--neon-red) !important; animation: pulse 1s infinite; }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .controls {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .btn {
            background: linear-gradient(145deg, var(--lcd-light) 0%, var(--lcd-dark) 100%);
            border: 2px solid var(--pixel-border);
            border-radius: 10px;
            padding: 15px 10px;
            color: #fff;
            font-family: inherit;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 0 var(--pixel-border);
        }

        .btn:hover {
            transform: translateY(2px);
            box-shadow: 0 2px 0 var(--pixel-border);
        }

        .btn:active {
            transform: translateY(4px);
            box-shadow: none;
        }

        .btn-primary {
            border-color: var(--neon-pink);
            box-shadow: 0 4px 0 rgba(255, 107, 157, 0.3);
        }

        .btn-primary:hover {
            box-shadow: 0 2px 0 rgba(255, 107, 157, 0.3);
        }

        .character-select {
            display: none;
            text-align: center;
        }

        .character-select.active {
            display: block;
        }

        .character-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .character-card {
            background: var(--lcd-light);
            border-radius: 10px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
        }

        .character-card:hover {
            border-color: var(--neon-pink);
            transform: scale(1.05);
        }

        .character-card.owned {
            border-color: var(--neon-green);
        }

        .character-card.owned::after {
            content: '★';
            position: absolute;
            top: 5px;
            right: 5px;
            color: var(--neon-yellow);
            font-size: 16px;
        }

        .character-card .pixel-art {
            width: 60px;
            height: 60px;
            margin: 0 auto 10px;
        }

        .character-card .name {
            font-size: 10px;
            text-transform: uppercase;
        }

        .game-screen {
            display: none;
        }

        .game-screen.active {
            display: block;
        }

        .actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 15px;
        }

        .action-btn {
            background: linear-gradient(145deg, var(--lcd-light) 0%, var(--lcd-dark) 100%);
            border: 2px solid var(--pixel-border);
            border-radius: 8px;
            padding: 12px;
            color: #fff;
            font-family: inherit;
            font-size: 10px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s;
        }

        .action-btn:hover {
            border-color: var(--neon-blue);
        }

        .action-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .special-actions {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid var(--pixel-border);
        }

        .special-actions h3 {
            font-size: 10px;
            text-transform: uppercase;
            opacity: 0.7;
            margin-bottom: 10px;
            text-align: center;
        }

        .notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--lcd-light);
            border: 2px solid var(--neon-pink);
            border-radius: 10px;
            padding: 15px 25px;
            font-size: 12px;
            text-transform: uppercase;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }

        .notification.show {
            opacity: 1;
        }

        .dead-screen {
            display: none;
            text-align: center;
            padding: 40px 20px;
            animation: deathPulse 0.5s ease-in-out;
        }

        .dead-screen.active {
            display: block;
        }

        .dead-screen.dramatic-death {
            animation: dramaticDeath 2s ease-in-out;
        }

        .dead-screen h2 {
            color: var(--neon-red);
            font-size: 32px;
            margin-bottom: 20px;
            text-shadow: 0 0 20px var(--neon-red);
            animation: flicker 1.5s infinite;
        }

        .dead-screen p {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 30px;
            color: #fff;
            line-height: 1.6;
        }

        .dead-screen .death-icon {
            font-size: 64px;
            margin-bottom: 20px;
            animation: skullShake 0.5s ease-in-out infinite;
        }

        @keyframes deathPulse {
            0% { transform: scale(0.8); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }

        @keyframes dramaticDeath {
            0% { transform: scale(1); filter: brightness(1); }
            10% { transform: scale(1.1); filter: brightness(2); }
            20% { transform: scale(0.9); filter: brightness(0.5); }
            30% { transform: scale(1.05); filter: brightness(1.5); }
            40% { transform: scale(0.95); filter: brightness(0.3); }
            50% { transform: scale(1.02); filter: brightness(1.2); }
            100% { transform: scale(1); filter: brightness(1); }
        }

        @keyframes flicker {
            0%, 100% { opacity: 1; text-shadow: 0 0 20px var(--neon-red); }
            50% { opacity: 0.7; text-shadow: 0 0 30px var(--neon-red), 0 0 50px #ff0000; }
        }

        @keyframes skullShake {
            0%, 100% { transform: rotate(-5deg); }
            50% { transform: rotate(5deg); }
        }

        .dead-character-art {
            width: 150px;
            height: 100px;
            margin: 0 auto 20px;
            filter: grayscale(50%) brightness(0.7);
            animation: deadFloat 3s ease-in-out infinite;
        }

        .dead-character-art svg {
            width: 100%;
            height: 100%;
        }

        @keyframes deadFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .sleeping-overlay {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            border-radius: 15px;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 20px;
        }

        .sleeping-overlay.active {
            display: flex;
        }

        .sleeping-overlay .zZZ {
            font-size: 48px;
            animation: float 2s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .nav-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .intro-text {
            font-size: 12px;
            line-height: 1.6;
            margin: 20px 0;
            opacity: 0.9;
        }

        @media (max-width: 480px) {
            .device {
                padding: 20px;
            }
            
            .character-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="device">
        <div class="device-header">
            <img src="https://welikeshooting.com/wp-content/uploads/2020/05/wls-bumper-sticker-e1588357006126.png" alt="WLS Logo" style="max-width: 200px; margin-bottom: 10px; filter: drop-shadow(0 0 10px rgba(255, 107, 157, 0.5));">
            <h1>★ WLS-agotchi ★</h1>
        </div>

        <div class="screen">
            <!-- Character Selection Screen -->
            <div class="character-select active" id="selectScreen">
                <h2 style="font-size: 16px; margin-bottom: 10px;">ADOPT A PODCASTER</h2>
                <p class="intro-text">Choose your We Like Shooting cast member to care for!</p>
                <div class="character-grid" id="characterGrid"></div>
            </div>

            <!-- Game Screen -->
            <div class="game-screen" id="gameScreen">
                <div class="character-display">
                    <div class="pixel-art" id="characterArt"></div>
                    <div class="character-name" id="characterName"></div>
                    <div class="character-status" id="characterStatus"></div>
                </div>

                <div class="stats">
                    <div class="stat stat-hunger">
                        <div class="stat-label">🍕 Ammo</div>
                        <div class="stat-bar">
                            <div class="stat-fill" id="hungerBar" style="width: 50%"></div>
                        </div>
                    </div>
                    <div class="stat stat-happiness">
                        <div class="stat-label">😊 Happy</div>
                        <div class="stat-bar">
                            <div class="stat-fill" id="happinessBar" style="width: 50%"></div>
                        </div>
                    </div>
                    <div class="stat stat-energy">
                        <div class="stat-label">⚡ Energy</div>
                        <div class="stat-bar">
                            <div class="stat-fill" id="energyBar" style="width: 50%"></div>
                        </div>
                    </div>
                    <div class="stat stat-health">
                        <div class="stat-label">❤️ Health</div>
                        <div class="stat-bar">
                            <div class="stat-fill" id="healthBar" style="width: 50%"></div>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button class="action-btn" onclick="game.feed()">🍕 Ammo Up</button>
                    <button class="action-btn" onclick="game.play()">🎯 Train</button>
                    <button class="action-btn" onclick="game.sleep()">😴 Sleep</button>
                    <button class="action-btn" onclick="game.medicine()">💊 Meds</button>
                </div>

                <div class="special-actions">
                    <h3>Special Actions</h3>
                    <div class="actions" id="specialActions"></div>
                </div>
            </div>

            <!-- Death Screen -->
            <div class="dead-screen" id="deadScreen">
                <div class="dead-character-art" id="deadCharacterArt"></div>
                <h2>THEY QUIT THE PODCAST!</h2>
                <p id="deathMessage"></p>
                <div style="margin-top: 20px; font-size: 12px; opacity: 0.7; color: var(--neon-red);">
                    💀 The chaos was too much 💀
                </div>
                <button class="btn btn-primary" onclick="game.revive()" style="margin-top: 30px;">🔄 Beg Them to Return</button>
            </div>

            <!-- Sleeping Overlay -->
            <div class="sleeping-overlay" id="sleepOverlay">
                <div class="zZZ">💤 zZZ</div>
                <button class="btn" onclick="game.wakeUp()">Wake Up</button>
            </div>
        </div>

        <div class="controls">
            <button class="btn" onclick="game.showSelect()">🏠 Home</button>
            <button class="btn btn-primary" onclick="game.switchCharacter()">👥 Switch</button>
            <button class="btn" onclick="game.save()">💾 Save</button>
        </div>
    </div>

    <div class="notification" id="notification"></div>

    <script>
        // Character Data
        const CHARACTERS = {
            shawn: {
                id: 'shawn',
                name: 'SHAWN',
                blurb: 'THE RINGMASTER! Keeps this circus from burning down. Finger on the mute button and the trigger.',
                color: '#ff6b9d',
                specialActions: [
                    { id: 'shoot', name: '🔫 Go Shooting', effect: 'happiness', amount: 50, cost: 20 },
                    { id: 'code', name: '💻 Code', effect: 'happiness', amount: 15, cost: 10 },
                    { id: 'mute', name: '🔇 Mute J', effect: 'happiness', amount: 25, cost: 5 }
                ],
                svg: `<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                    <!-- Shawn - Ringmaster, brown hair, long beard, tall, baseball cap -->
                    <rect x="12" y="4" width="8" height="4" fill="#8B4513"/>
                    <rect x="10" y="6" width="12" height="2" fill="#DC143C"/>
                    <rect x="13" y="2" width="6" height="2" fill="#DC143C"/>
                    <rect x="12" y="8" width="8" height="6" fill="#FFDBAC"/>
                    <rect x="10" y="10" width="2" height="2" fill="#FFDBAC"/>
                    <rect x="20" y="10" width="2" height="2" fill="#FFDBAC"/>
                    <rect x="14" y="12" width="1" height="1" fill="#333"/>
                    <rect x="17" y="12" width="1" height="1" fill="#333"/>
                    <rect x="14" y="14" width="4" height="1" fill="#8B4513"/>
                    <rect x="11" y="14" width="3" height="4" fill="#8B4513"/>
                    <rect x="18" y="14" width="3" height="4" fill="#8B4513"/>
                    <rect x="12" y="18" width="8" height="8" fill="#2C3E50"/>
                    <rect x="12" y="18" width="8" height="3" fill="#34495E"/>
                    <rect x="10" y="20" width="2" height="6" fill="#FFDBAC"/>
                    <rect x="20" y="20" width="2" height="6" fill="#FFDBAC"/>
                    <rect x="12" y="26" width="3" height="4" fill="#1a1a2e"/>
                    <rect x="17" y="26" width="3" height="4" fill="#1a1a2e"/>
                </svg>`
            },
            jeremy: {
                id: 'jeremy',
                name: 'JEREMY',
                blurb: 'THE BATTLE ORC! Hates everyone and thinks Thanos should\'ve aimed for gun grabbers first.',
                color: '#e74c3c',
                specialActions: [
                    { id: 'rant', name: '🗣️ Gun Rant', effect: 'happiness', amount: 10, cost: 15 },
                    { id: 'shoot', name: '🔫 Mag Dump', effect: 'happiness', amount: 35, cost: 20 }
                ],
                svg: `<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                    <!-- Jeremy - Battle Orc, Giant, clean-shaven, baseball cap -->
                    <rect x="10" y="4" width="12" height="4" fill="#2C3E50"/>
                    <rect x="8" y="6" width="16" height="2" fill="#e74c3c"/>
                    <rect x="11" y="8" width="10" height="8" fill="#FFDBAC"/>
                    <rect x="9" y="10" width="2" height="4" fill="#FFDBAC"/>
                    <rect x="22" y="10" width="2" height="4" fill="#FFDBAC"/>
                    <rect x="13" y="12" width="2" height="1" fill="#333"/>
                    <rect x="17" y="12" width="2" height="1" fill="#333"/>
                    <rect x="14" y="15" width="4" height="1" fill="#c0392b"/>
                    <rect x="10" y="16" width="12" height="10" fill="#2C3E50"/>
                    <rect x="10" y="16" width="12" height="4" fill="#34495E"/>
                    <rect x="8" y="18" width="2" height="8" fill="#FFDBAC"/>
                    <rect x="22" y="18" width="2" height="8" fill="#FFDBAC"/>
                    <rect x="10" y="26" width="4" height="4" fill="#1a1a2e"/>
                    <rect x="18" y="26" width="4" height="4" fill="#1a1a2e"/>
                </svg>`
            },
            nick: {
                id: 'nick',
                name: 'NICK',
                blurb: 'THE COOL ONE! High-speed, low-drag, always looking cinematic with the best gear.',
                color: '#3498db',
                specialActions: [
                    { id: 'gear', name: '🕶️ New Gear', effect: 'happiness', amount: 20, cost: 10 },
                    { id: 'shoot', name: '🔫 Range Day', effect: 'happiness', amount: 35, cost: 25 }
                ],
                svg: `<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                    <!-- Nick - Cool one, young, brown hair, short beard -->
                    <rect x="12" y="4" width="8" height="4" fill="#5D4037"/>
                    <rect x="11" y="8" width="10" height="7" fill="#FFDBAC"/>
                    <rect x="11" y="14" width="10" height="2" fill="#5D4037"/>
                    <rect x="13" y="15" width="1" height="1" fill="#5D4037"/>
                    <rect x="18" y="15" width="1" height="1" fill="#5D4037"/>
                    <rect x="9" y="10" width="2" height="3" fill="#FFDBAC"/>
                    <rect x="21" y="10" width="2" height="3" fill="#FFDBAC"/>
                    <rect x="14" y="12" width="1" height="1" fill="#333"/>
                    <rect x="17" y="12" width="1" height="1" fill="#333"/>
                    <rect x="14" y="14" width="4" height="1" fill="#D2691E"/>
                    <rect x="11" y="17" width="10" height="9" fill="#2980b9"/>
                    <rect x="11" y="17" width="10" height="3" fill="#3498db"/>
                    <rect x="9" y="19" width="2" height="6" fill="#FFDBAC"/>
                    <rect x="21" y="19" width="2" height="6" fill="#FFDBAC"/>
                    <rect x="11" y="26" width="4" height="4" fill="#1a1a2e"/>
                    <rect x="17" y="26" width="4" height="4" fill="#1a1a2e"/>
                </svg>`
            },
            savage: {
                id: 'savage',
                name: 'SAVAGE',
                blurb: 'TOTAL TRAINWRECK! NFA violations, binary triggers, and picking his nose while shooting.',
                color: '#f39c12',
                specialActions: [
                    { id: 'binary', name: '⚡ Binary Trigger', effect: 'random', amount: 0, cost: 0 },
                    { id: 'nose', name: '👃 Pick & Shoot', effect: 'happiness', amount: 15, cost: 5 }
                ],
                svg: `<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                    <!-- Savage - Bald, short, chaos -->
                    <rect x="12" y="6" width="8" height="8" fill="#FFDBAC"/>
                    <rect x="10" y="8" width="2" height="3" fill="#FFDBAC"/>
                    <rect x="20" y="8" width="2" height="3" fill="#FFDBAC"/>
                    <rect x="14" y="10" width="1" height="2" fill="#333"/>
                    <rect x="17" y="10" width="1" height="2" fill="#333"/>
                    <rect x="14" y="13" width="4" height="1" fill="#e74c3c"/>
                    <rect x="11" y="14" width="10" height="10" fill="#8e44ad"/>
                    <rect x="11" y="14" width="10" height="3" fill="#9b59b6"/>
                    <rect x="9" y="16" width="2" height="6" fill="#FFDBAC"/>
                    <rect x="21" y="16" width="2" height="6" fill="#FFDBAC"/>
                    <rect x="11" y="24" width="4" height="6" fill="#1a1a2e"/>
                    <rect x="17" y="24" width="4" height="6" fill="#1a1a2e"/>
                </svg>`
            },
            aaron: {
                id: 'aaron',
                name: 'AARON',
                blurb: 'THE HYPE! Here for the vibes, the new guns, and conning his way to the best deals.',
                color: '#9b59b6',
                specialActions: [
                    { id: 'hype', name: '🎉 Hype Train', effect: 'all', amount: 10, cost: 15 },
                    { id: 'deal', name: '💰 Gun Deal', effect: 'happiness', amount: 25, cost: 10 }
                ],
                svg: `<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                    <!-- Aaron - Old, gray beard, baseball cap -->
                    <rect x="12" y="4" width="8" height="4" fill="#7f8c8d"/>
                    <rect x="10" y="6" width="12" height="2" fill="#f39c12"/>
                    <rect x="11" y="8" width="10" height="8" fill="#FFDBAC"/>
                    <rect x="11" y="14" width="10" height="3" fill="#7f8c8d"/>
                    <rect x="9" y="10" width="2" height="3" fill="#FFDBAC"/>
                    <rect x="21" y="10" width="2" height="3" fill="#FFDBAC"/>
                    <rect x="14" y="12" width="1" height="1" fill="#333"/>
                    <rect x="17" y="12" width="1" height="1" fill="#333"/>
                    <rect x="14" y="14" width="4" height="1" fill="#95a5a6"/>
                    <rect x="11" y="17" width="10" height="9" fill="#27ae60"/>
                    <rect x="11" y="17" width="10" height="3" fill="#2ecc71"/>
                    <rect x="9" y="19" width="2" height="6" fill="#FFDBAC"/>
                    <rect x="21" y="19" width="2" height="6" fill="#FFDBAC"/>
                    <rect x="11" y="26" width="4" height="4" fill="#1a1a2e"/>
                    <rect x="17" y="26" width="4" height="4" fill="#1a1a2e"/>
                </svg>`
            }
        };

        // Game State
        class TamagotchiGame {
            constructor() {
                this.pets = {};
                this.currentPetId = null;
                this.lastTick = Date.now();
                this.tickRate = 8000; // 8 seconds (faster decay for quick game)
                this.decayRate = 5; // stats decay by 5 per tick (aggressive)
                
                this.load();
                this.init();
                this.startGameLoop();
            }

            init() {
                this.renderCharacterSelect();
                if (this.currentPetId && this.pets[this.currentPetId]) {
                    this.showGame();
                } else {
                    this.showSelect();
                }
            }

            load() {
                const saved = localStorage.getItem('wlsagotchi');
                if (saved) {
                    const data = JSON.parse(saved);
                    this.pets = data.pets || {};
                    this.currentPetId = data.currentPetId || null;
                    this.lastTick = data.lastTick || Date.now();
                    
                    // Calculate offline decay
                    const offlineTime = Date.now() - this.lastTick;
                    const ticks = Math.floor(offlineTime / this.tickRate);
                    this.applyDecay(ticks);
                }
            }

            save() {
                const data = {
                    pets: this.pets,
                    currentPetId: this.currentPetId,
                    lastTick: Date.now()
                };
                localStorage.setItem('wlsagotchi', JSON.stringify(data));
                this.showNotification('Game Saved!');
            }

            applyDecay(ticks) {
                if (ticks <= 0) return;
                
                Object.values(this.pets).forEach(pet => {
                    if (pet.isSleeping) return; // Don't decay while sleeping
                    
                    for (let i = 0; i < ticks; i++) {
                        pet.hunger = Math.max(0, pet.hunger - this.decayRate);
                        pet.happiness = Math.max(0, pet.happiness - this.decayRate);
                        pet.energy = Math.max(0, pet.energy - this.decayRate);
                        
                        // Health always drains slowly (living is hard)
                        pet.health = Math.max(0, pet.health - 2);
                        
                        // Health drops FASTER if other stats are low
                        if (pet.hunger < 30 || pet.happiness < 30 || pet.energy < 30) {
                            pet.health = Math.max(0, pet.health - this.decayRate);
                        }
                        
                        // Critical warning when health is low
                        if (pet.health <= 10 && pet.health > 0) {
                            // They'll die next tick if not healed
                        }
                    }
                });
            }

            startGameLoop() {
                setInterval(() => {
                    this.applyDecay(1);
                    this.lastTick = Date.now();
                    if (this.currentPetId) {
                        const pet = this.pets[this.currentPetId];
                        if (pet && pet.health <= 0) {
                            this.triggerDeath();
                        } else {
                            this.updateDisplay();
                        }
                    }
                }, this.tickRate);
            }

            triggerDeath() {
                const pet = this.pets[this.currentPetId];
                if (!pet || pet.dead) return;
                
                pet.dead = true;
                this.save();
                this.showDead();
                
                // Dramatic death effects
                const deadScreen = document.getElementById('deadScreen');
                deadScreen.classList.add('dramatic-death');
                
                // Play death sound effect (visual notification)
                this.showNotification('💀 THEY QUIT! 💀');
            }

            renderCharacterSelect() {
                const grid = document.getElementById('characterGrid');
                grid.innerHTML = '';
                
                Object.values(CHARACTERS).forEach(char => {
                    const card = document.createElement('div');
                    card.className = 'character-card' + (this.pets[char.id] ? ' owned' : '');
                    card.innerHTML = `
                        <div class="pixel-art">${char.svg}</div>
                        <div class="name">${char.name}</div>
                    `;
                    card.onclick = () => this.adoptOrSelect(char.id);
                    grid.appendChild(card);
                });
            }

            adoptOrSelect(charId) {
                if (this.pets[charId]) {
                    this.currentPetId = charId;
                    this.showGame();
                } else {
                    this.adopt(charId);
                }
            }

            adopt(charId) {
                const char = CHARACTERS[charId];
                this.pets[charId] = {
                    id: charId,
                    name: char.name,
                    hunger: 80,
                    happiness: 80,
                    energy: 80,
                    health: 100,
                    isSleeping: false,
                    adoptedAt: Date.now()
                };
                this.currentPetId = charId;
                this.save();
                this.showGame();
                this.showNotification(`Adopted ${char.name}!`);
            }

            showSelect() {
                document.getElementById('selectScreen').classList.add('active');
                document.getElementById('gameScreen').classList.remove('active');
                document.getElementById('deadScreen').classList.remove('active');
                this.renderCharacterSelect();
            }

            showGame() {
                const pet = this.pets[this.currentPetId];
                if (!pet) return;

                if (pet.health <= 0) {
                    this.showDead();
                    return;
                }

                document.getElementById('selectScreen').classList.remove('active');
                document.getElementById('gameScreen').classList.add('active');
                document.getElementById('deadScreen').classList.remove('active');

                const char = CHARACTERS[pet.id];
                document.getElementById('characterArt').innerHTML = char.svg;
                document.getElementById('characterName').textContent = char.name;
                document.getElementById('characterName').style.color = char.color;
                document.getElementById('characterStatus').textContent = pet.isSleeping ? 'Sleeping...' : 'Active';

                // Render special actions
                const specialContainer = document.getElementById('specialActions');
                specialContainer.innerHTML = '<h3>Special Actions</h3>';
                const actionsDiv = document.createElement('div');
                actionsDiv.className = 'actions';
                
                char.specialActions.forEach(action => {
                    const btn = document.createElement('button');
                    btn.className = 'action-btn';
                    btn.textContent = action.name;
                    btn.disabled = pet.isSleeping;
                    btn.onclick = () => this.specialAction(action);
                    actionsDiv.appendChild(btn);
                });
                
                specialContainer.appendChild(actionsDiv);
                this.updateDisplay();
            }

            showDead() {
                document.getElementById('selectScreen').classList.remove('active');
                document.getElementById('gameScreen').classList.remove('active');
                document.getElementById('deadScreen').classList.add('active');
                
                const char = CHARACTERS[this.currentPetId];
                const deathMessages = [
                    `${char.name} stormed out of the studio! The chaos was too much.`,
                    `${char.name} rage-quit live on air! No coming back from that.`,
                    `${char.name} couldn't handle Jeremy anymore. Podcast over.`,
                    `${char.name} said "I'm out!" and slammed the door.`,
                    `${char.name} quit mid-recording. The mute button wasn't enough.`
                ];
                const randomMessage = deathMessages[Math.floor(Math.random() * deathMessages.length)];
                document.getElementById('deathMessage').textContent = randomMessage;
                
                // Show dead pixel art
                const deadArt = this.createDeadPixelArt(char.color);
                document.getElementById('deadCharacterArt').innerHTML = deadArt;
            }

            createDeadPixelArt(color) {
                return `<svg viewBox="0 0 48 32" xmlns="http://www.w3.org/2000/svg">
                    <!-- Ground -->
                    <rect x="0" y="28" width="48" height="4" fill="#2d2d44"/>
                    <!-- Body laying on back -->
                    <rect x="8" y="20" width="24" height="6" fill="#2C3E50"/>
                    <rect x="8" y="20" width="24" height="2" fill="#34495E"/>
                    <!-- Head -->
                    <rect x="32" y="18" width="8" height="8" fill="#FFDBAC"/>
                    <!-- X eyes -->
                    <rect x="33" y="20" width="2" height="2" fill="#333"/>
                    <rect x="33" y="20" width="1" height="1" fill="#fff"/>
                    <rect x="34" y="21" width="1" height="1" fill="#fff"/>
                    <rect x="36" y="20" width="2" height="2" fill="#333"/>
                    <rect x="36" y="20" width="1" height="1" fill="#fff"/>
                    <rect x="37" y="21" width="1" height="1" fill="#fff"/>
                    <!-- Arms crossed on chest -->
                    <rect x="12" y="21" width="6" height="2" fill="#FFDBAC"/>
                    <rect x="22" y="21" width="6" height="2" fill="#FFDBAC"/>
                    <!-- Legs -->
                    <rect x="8" y="26" width="4" height="2" fill="#1a1a2e"/>
                    <rect x="28" y="26" width="4" height="2" fill="#1a1a2e"/>
                    <!-- Microphone on chest like a flower -->
                    <rect x="18" y="18" width="4" height="8" fill="#2c3e50"/>
                    <rect x="17" y="16" width="6" height="4" fill="#34495e"/>
                    <rect x="18" y="17" width="4" height="2" fill="#7f8c8d"/>
                    <!-- Mic cord -->
                    <rect x="19" y="26" width="2" height="4" fill="#1a1a2e"/>
                    <rect x="20" y="28" width="8" height="1" fill="#1a1a2e"/>
                </svg>`;
            }

            updateDisplay() {
                const pet = this.pets[this.currentPetId];
                if (!pet || pet.health <= 0) return;

                this.updateBar('hunger', pet.hunger);
                this.updateBar('happiness', pet.happiness);
                this.updateBar('energy', pet.energy);
                this.updateBar('health', pet.health);

                document.getElementById('characterStatus').textContent = pet.isSleeping ? 'Sleeping...' : 'Active';
                
                const sleepOverlay = document.getElementById('sleepOverlay');
                if (pet.isSleeping) {
                    sleepOverlay.classList.add('active');
                } else {
                    sleepOverlay.classList.remove('active');
                }
            }

            updateBar(stat, value) {
                const bar = document.getElementById(stat + 'Bar');
                bar.style.width = value + '%';
                bar.classList.toggle('critical', value < 20);
                
                // Special critical styling for health
                if (stat === 'health' && value <= 10 && value > 0) {
                    bar.style.animation = 'pulse 0.3s infinite';
                    bar.style.background = '#ff0000';
                } else if (stat === 'health') {
                    bar.style.animation = '';
                    bar.style.background = '';
                }
            }

            feed() {
                const pet = this.pets[this.currentPetId];
                if (pet.isSleeping || pet.health <= 0) return;
                
                pet.hunger = Math.min(100, pet.hunger + 20);
                pet.energy = Math.max(0, pet.energy - 5);
                this.updateDisplay();
                this.showNotification('Locked & Loaded! 🍕');
                this.animate('eat');
            }

            play() {
                const pet = this.pets[this.currentPetId];
                if (pet.isSleeping || pet.health <= 0) return;
                
                pet.happiness = Math.min(100, pet.happiness + 15);
                pet.energy = Math.max(0, pet.energy - 15);
                pet.hunger = Math.max(0, pet.hunger - 10);
                this.updateDisplay();
                this.showNotification('On Target! 🎯');
                this.animate('play');
            }

            sleep() {
                const pet = this.pets[this.currentPetId];
                if (pet.health <= 0) return;
                
                pet.isSleeping = true;
                this.updateDisplay();
                this.showNotification('Goodnight! 😴');
            }

            wakeUp() {
                const pet = this.pets[this.currentPetId];
                pet.isSleeping = false;
                pet.energy = Math.min(100, pet.energy + 30);
                this.updateDisplay();
                this.showNotification('Good morning! ☀️');
            }

            medicine() {
                const pet = this.pets[this.currentPetId];
                if (pet.isSleeping || pet.health <= 0) return;
                
                pet.health = Math.min(100, pet.health + 25);
                this.updateDisplay();
                this.showNotification('Feeling better! 💊');
            }

            specialAction(action) {
                const pet = this.pets[this.currentPetId];
                if (pet.isSleeping || pet.health <= 0) return;
                
                if (pet.energy < action.cost) {
                    this.showNotification('Too tired! 😫');
                    return;
                }

                pet.energy = Math.max(0, pet.energy - action.cost);

                if (action.effect === 'happiness') {
                    pet.happiness = Math.min(100, pet.happiness + action.amount);
                    this.showNotification(`+${action.amount} Happiness!`);
                } else if (action.effect === 'all') {
                    pet.hunger = Math.min(100, pet.hunger + action.amount);
                    pet.happiness = Math.min(100, pet.happiness + action.amount);
                    pet.energy = Math.min(100, pet.energy + action.amount);
                    pet.health = Math.min(100, pet.health + action.amount);
                    this.showNotification('All stats boosted!');
                } else if (action.effect === 'random') {
                    // Chaos effect - random stat changes
                    const stats = ['hunger', 'happiness', 'energy', 'health'];
                    const randomStat = stats[Math.floor(Math.random() * stats.length)];
                    const randomChange = Math.floor(Math.random() * 40) - 10; // -10 to +30
                    pet[randomStat] = Math.max(0, Math.min(100, pet[randomStat] + randomChange));
                    this.showNotification('CHAOS! 🎲');
                }

                this.updateDisplay();
            }

            revive() {
                const pet = this.pets[this.currentPetId];
                pet.health = 50;
                pet.hunger = 50;
                pet.happiness = 50;
                pet.energy = 50;
                pet.isSleeping = false;
                pet.dead = false;
                this.save();
                this.showGame();
                this.showNotification('They came back! The show must go on! 🎉');
            }

            switchCharacter() {
                this.showSelect();
            }

            animate(type) {
                const art = document.getElementById('characterArt');
                art.style.transform = type === 'eat' ? 'scale(1.1)' : 'translateX(5px)';
                setTimeout(() => {
                    art.style.transform = '';
                }, 300);
            }

            showNotification(message) {
                const notif = document.getElementById('notification');
                notif.textContent = message;
                notif.classList.add('show');
                setTimeout(() => {
                    notif.classList.remove('show');
                }, 2000);
            }
        }

        // Initialize game
        const game = new TamagotchiGame();
    </script>
</body>
</html>
