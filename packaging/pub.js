// Visuel publicitaire 1920×1080 : le sac Lylium en scène.
const fs = require('fs');
const { chromium } = require('playwright');
const { bagDefs, bagBody, F, G, NAVY, GOLD, GOLD_DEEP } = require('./build.js');

const W = 1920, H = 1080;
const font = (family, file, weight = 400, style = 'normal') =>
  `@font-face{font-family:'${family}';font-weight:${weight};font-style:${style};src:url(data:font/woff2;base64,${
    fs.readFileSync(__dirname + '/fonts/' + file).toString('base64')}) format('woff2')}`;
const fontCss = [
  font('Cormorant Garamond', 'cormorant-garamond-latin-500-normal.woff2', 500),
  font('Cormorant Garamond', 'cormorant-garamond-latin-500-italic.woff2', 500, 'italic'),
  font('Inter', 'inter-latin-400-normal.woff2', 400),
  font('Inter', 'inter-latin-500-normal.woff2', 500),
].join('');

// Poussière d'or : pseudo-aléatoire déterministe
let seed = 7;
const rnd = () => (seed = (seed * 16807) % 2147483647) / 2147483647;
const dust = Array.from({ length: 90 }, () => {
  const x = 980 + rnd() * 860, y = 60 + rnd() * 820, r = 0.6 + rnd() ** 3 * 4.5;
  return `<circle cx="${x.toFixed(1)}" cy="${y.toFixed(1)}" r="${r.toFixed(2)}" fill="${GOLD}" opacity="${(0.25 + rnd() * 0.6).toFixed(2)}"${r > 3 ? ' filter="url(#bokeh)"' : ''}/>`;
}).join('');

// Placement du sac sur le socle
const S = 0.8, CX = 1400, TOP = 872;                     // échelle, centre du socle, dessus du socle
const bagCx = F.x + (F.w + G) / 2, bagBottom = F.y + F.h;
const tx = CX - bagCx * S, ty = TOP + 26 - bagBottom * S;

const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${W}" height="${H}" viewBox="0 0 ${W} ${H}">
  <defs>${bagDefs}
    <style>${fontCss}</style>
    <radialGradient id="scene" cx=".7" cy=".42" r=".85">
      <stop offset="0" stop-color="#25216a"/><stop offset=".45" stop-color="${NAVY}"/><stop offset="1" stop-color="#020108"/>
    </radialGradient>
    <linearGradient id="beam" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0" stop-color="#fff3d6" stop-opacity=".55"/><stop offset="1" stop-color="#fff3d6" stop-opacity="0"/>
    </linearGradient>
    <linearGradient id="plinth" x1="0" y1="0" x2="1" y2="0">
      <stop offset="0" stop-color="#05041a"/><stop offset=".35" stop-color="#1d1a55"/>
      <stop offset=".55" stop-color="#14123f"/><stop offset="1" stop-color="#030210"/>
    </linearGradient>
    <radialGradient id="plinthTop" cx=".5" cy=".4" r=".6">
      <stop offset="0" stop-color="#2c2878"/><stop offset="1" stop-color="#0b0a2a"/>
    </radialGradient>
    <linearGradient id="floor" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0" stop-color="#000" stop-opacity="0"/><stop offset="1" stop-color="#000" stop-opacity=".7"/>
    </linearGradient>
    <linearGradient id="goldText" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0" stop-color="#ffe6ae"/><stop offset=".55" stop-color="${GOLD}"/><stop offset="1" stop-color="${GOLD_DEEP}"/>
    </linearGradient>
    <filter id="beamBlur" x="-30%" y="-10%" width="160%" height="120%"><feGaussianBlur stdDeviation="30"/></filter>
    <filter id="bokeh"><feGaussianBlur stdDeviation="1.6"/></filter>
    <filter id="glow" x="-50%" y="-50%" width="200%" height="200%"><feGaussianBlur stdDeviation="60"/></filter>
  </defs>

  <rect width="${W}" height="${H}" fill="url(#scene)"/>
  <!-- grande fleur de lys en filigrane -->
  <use href="#fleur" x="-120" y="120" width="900" height="900" fill="${GOLD}" opacity=".035"/>

  <!-- halo + faisceau de lumière -->
  <ellipse cx="${CX}" cy="520" rx="420" ry="360" fill="#3b36a0" opacity=".35" filter="url(#glow)"/>
  <path d="M${CX - 120},-40 L${CX + 120},-40 L${CX + 470},${TOP + 20} L${CX - 470},${TOP + 20}Z" fill="url(#beam)" opacity=".38" filter="url(#beamBlur)"/>

  <!-- sol -->
  <rect y="${TOP + 60}" width="${W}" height="${H - TOP - 60}" fill="url(#floor)"/>

  <!-- socle -->
  <ellipse cx="${CX}" cy="${H + 10}" rx="440" ry="40" fill="#000" opacity=".6" filter="url(#bokeh)"/>
  <path d="M${CX - 380},${TOP} L${CX - 380},${H + 20} L${CX + 380},${H + 20} L${CX + 380},${TOP}Z" fill="url(#plinth)"/>
  <ellipse cx="${CX}" cy="${TOP}" rx="380" ry="52" fill="url(#plinthTop)"/>
  <ellipse cx="${CX}" cy="${TOP}" rx="380" ry="52" fill="none" stroke="url(#gold)" stroke-width="2.5"/>
  <ellipse cx="${CX}" cy="${TOP + 26}" rx="300" ry="24" fill="#000" opacity=".7" filter="url(#blur)"/>

  <!-- le sac -->
  <g transform="translate(${tx.toFixed(1)} ${ty.toFixed(1)}) scale(${S})">${bagBody}</g>

  <!-- poussière d'or -->
  <g>${dust}</g>

  <!-- texte -->
  <g transform="translate(150 0)">
    <text y="300" font-family="Inter" font-weight="500" font-size="15" letter-spacing="7" fill="${GOLD}" opacity=".85">NOUVELLE COLLECTION</text>
    <line x1="0" x2="60" y1="336" y2="336" stroke="${GOLD}" stroke-width="1.5" opacity=".7"/>
    <text y="470" font-family="'Great Vibes'" font-size="150" fill="url(#goldText)">L'élégance</text>
    <text y="575" x="40" font-family="'Cormorant Garamond'" font-style="italic" font-weight="500" font-size="62" fill="#f4ecdc">à la française.</text>
    <text y="668" font-family="'Cormorant Garamond'" font-weight="500" font-size="25" fill="#cfc8e6" opacity=".85">
      <tspan x="0">Pensé et confectionné en France,</tspan>
      <tspan x="0" dy="36">chaque pièce Lylium se porte comme une signature.</tspan>
    </text>
    <g transform="translate(0 790)">
      <rect width="300" height="58" fill="none" stroke="${GOLD}" stroke-width="1.5"/>
      <text x="150" y="35" text-anchor="middle" font-family="Inter" font-weight="500" font-size="14" letter-spacing="5" fill="${GOLD}">DÉCOUVRIR</text>
    </g>
  </g>

  <!-- signature -->
  <text x="150" y="${H - 64}" font-family="Inter" font-size="12" letter-spacing="5" fill="#8f89b8">MAISON FRANÇAISE · FLEUR DE LYS</text>
</svg>`;

fs.writeFileSync(__dirname + '/pub-lylium.svg', svg);
(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: W, height: H } });
  await page.setContent(`<html><body style="margin:0">${svg}</body></html>`);
  await page.evaluate(() => document.fonts.ready);
  await page.screenshot({ path: __dirname + '/pub-lylium.png' });
  await browser.close();
})();
