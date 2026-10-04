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

// Poussière d'or et bokeh : pseudo-aléatoire déterministe
let seed = 11;
const rnd = () => (seed = (seed * 16807) % 2147483647) / 2147483647;
const dust = Array.from({ length: 70 }, () => {
  const x = 380 + rnd() * 1160, y = 40 + rnd() * 760, r = 0.5 + rnd() ** 3 * 3.5;
  return `<circle cx="${x.toFixed(1)}" cy="${y.toFixed(1)}" r="${r.toFixed(2)}" fill="${GOLD}" opacity="${(0.2 + rnd() * 0.55).toFixed(2)}"${r > 2.5 ? ' filter="url(#bokeh)"' : ''}/>`;
}).join('');
const bokeh = Array.from({ length: 16 }, () => {
  const x = rnd() * W, y = 80 + rnd() * 700, r = 25 + rnd() * 70;
  return `<circle cx="${x.toFixed(0)}" cy="${y.toFixed(0)}" r="${r.toFixed(0)}" fill="${rnd() > .6 ? GOLD : '#6e68d8'}" opacity="${(0.04 + rnd() * 0.07).toFixed(3)}"/>`;
}).join('');

// Placement du sac, centré sur le socle
const S = 0.88, CX = W / 2, TOP = 905;                    // échelle, centre du socle, dessus du socle
const bagCx = F.x + (F.w + G) / 2, bagBottom = F.y + F.h;
const tx = CX - bagCx * S, ty = TOP + 22 - bagBottom * S;
const bag = `<g transform="translate(${tx.toFixed(1)} ${ty.toFixed(1)}) scale(${S})">${bagBody}</g>`;

const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${W}" height="${H}" viewBox="0 0 ${W} ${H}">
  <defs>${bagDefs}
    <radialGradient id="scene" cx=".5" cy=".42" r=".75">
      <stop offset="0" stop-color="#29247a"/><stop offset=".45" stop-color="${NAVY}"/><stop offset="1" stop-color="#020108"/>
    </radialGradient>
    <linearGradient id="beam" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0" stop-color="#fff3d6" stop-opacity=".6"/><stop offset="1" stop-color="#fff3d6" stop-opacity="0"/>
    </linearGradient>
    <linearGradient id="plinth" x1="0" y1="0" x2="1" y2="0">
      <stop offset="0" stop-color="#04030f"/><stop offset=".3" stop-color="#1a174d"/>
      <stop offset=".5" stop-color="#26226a"/><stop offset=".7" stop-color="#14123f"/><stop offset="1" stop-color="#030210"/>
    </linearGradient>
    <linearGradient id="plinthV" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0" stop-color="#000" stop-opacity="0"/><stop offset="1" stop-color="#000" stop-opacity=".6"/>
    </linearGradient>
    <radialGradient id="plinthTop" cx=".5" cy=".35" r=".65">
      <stop offset="0" stop-color="#36318a"/><stop offset="1" stop-color="#0b0a2a"/>
    </radialGradient>
    <linearGradient id="reflFade" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0" stop-color="#fff" stop-opacity=".22"/><stop offset=".35" stop-color="#fff" stop-opacity="0"/>
    </linearGradient>
    <mask id="reflMask" maskUnits="userSpaceOnUse" x="0" y="${TOP}" width="${W}" height="200">
      <rect x="0" y="${TOP + 10}" width="${W}" height="200" fill="url(#reflFade)"/>
    </mask>
    <clipPath id="topClip"><ellipse cx="${CX}" cy="${TOP}" rx="420" ry="58"/></clipPath>
    <filter id="beamBlur" x="-30%" y="-10%" width="160%" height="120%"><feGaussianBlur stdDeviation="34"/></filter>
    <filter id="bokeh"><feGaussianBlur stdDeviation="1.6"/></filter>
    <filter id="bokehBig"><feGaussianBlur stdDeviation="6"/></filter>
    <filter id="glow" x="-50%" y="-50%" width="200%" height="200%"><feGaussianBlur stdDeviation="70"/></filter>
    <filter id="reflBlur"><feGaussianBlur stdDeviation="2.5"/></filter>
    <filter id="vignetteGrain" x="0" y="0" width="100%" height="100%">
      <feTurbulence type="fractalNoise" baseFrequency=".8" numOctaves="2" seed="9"/>
      <feColorMatrix values="0 0 0 0 1  0 0 0 0 1  0 0 0 0 1  0 0 0 .035 0"/>
    </filter>
    <radialGradient id="vignette" cx=".5" cy=".5" r=".75">
      <stop offset=".6" stop-color="#000" stop-opacity="0"/><stop offset="1" stop-color="#000" stop-opacity=".65"/>
    </radialGradient>
  </defs>

  <rect width="${W}" height="${H}" fill="url(#scene)"/>
  <g filter="url(#bokehBig)">${bokeh}</g>

  <!-- halo + faisceau de lumière -->
  <ellipse cx="${CX}" cy="540" rx="520" ry="400" fill="#3d37a8" opacity=".35" filter="url(#glow)"/>
  <path d="M${CX - 140},-40 L${CX + 140},-40 L${CX + 560},${TOP + 30} L${CX - 560},${TOP + 30}Z" fill="url(#beam)" opacity=".34" filter="url(#beamBlur)"/>

  <!-- socle -->
  <ellipse cx="${CX}" cy="${H + 12}" rx="500" ry="44" fill="#000" opacity=".7" filter="url(#bokeh)"/>
  <rect x="${CX - 420}" y="${TOP}" width="840" height="${H - TOP + 20}" fill="url(#plinth)"/>
  <rect x="${CX - 420}" y="${TOP}" width="840" height="${H - TOP + 20}" fill="url(#plinthV)"/>
  <ellipse cx="${CX}" cy="${TOP}" rx="420" ry="58" fill="url(#plinthTop)"/>
  <!-- reflet du sac sur le dessus laqué du socle -->
  <g clip-path="url(#topClip)">
    <g mask="url(#reflMask)" filter="url(#reflBlur)">
      <g transform="translate(0 ${2 * (TOP + 22)}) scale(1 -1)">${bag}</g>
    </g>
  </g>
  <ellipse cx="${CX}" cy="${TOP}" rx="420" ry="58" fill="none" stroke="url(#gold)" stroke-width="2.5"/>
  <path d="M${CX - 420},${TOP} A420,58 0 0 0 ${CX + 420},${TOP}" fill="none" stroke="#ffe2a3" stroke-opacity=".35" stroke-width="1" transform="translate(0 3)"/>
  <!-- ombre de contact -->
  <ellipse cx="${CX + 30}" cy="${TOP + 20}" rx="330" ry="22" fill="#000" opacity=".75" filter="url(#blur)"/>

  ${bag}

  <g>${dust}</g>
  <rect width="${W}" height="${H}" fill="url(#vignette)"/>
  <rect width="${W}" height="${H}" filter="url(#vignetteGrain)"/>
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
