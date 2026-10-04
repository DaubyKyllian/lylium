// Génère le motif et la maquette du sac Lylium, puis les rend en PNG.
const fs = require('fs');
const { chromium } = require('playwright');

const NAVY = '#0e0c32', GOLD = '#f7bb57', GOLD_DEEP = '#c98f35';
const motifs = fs.readFileSync(__dirname + '/motifs.svgfrag', 'utf8');
const scriptFont = fs.readFileSync(__dirname + '/fonts/great-vibes-latin-400-normal.woff2').toString('base64');
const tile = fs.readFileSync(__dirname + '/tile.svgfrag', 'utf8');

const defsCommon = `
  ${motifs}
  <pattern id="monogram" width="261" height="336" patternUnits="userSpaceOnUse" patternTransform="scale(.34) translate(40 150)">
    <rect width="261" height="336" fill="${NAVY}"/>
    <g fill="${GOLD}">${tile}</g>
  </pattern>
  <linearGradient id="gold" x1="0" y1="0" x2="1" y2="1">
    <stop offset="0" stop-color="#ffe2a3"/><stop offset=".45" stop-color="${GOLD}"/>
    <stop offset="1" stop-color="${GOLD_DEEP}"/>
  </linearGradient>`;

// Motif seul (fichier d'impression)
const tileSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="1044" height="1008" viewBox="0 0 1044 1008">
  <defs>${motifs}
    <pattern id="p" width="261" height="336" patternUnits="userSpaceOnUse">
      <rect width="261" height="336" fill="${NAVY}"/><g fill="${GOLD}">${tile}</g>
    </pattern></defs>
  <rect width="1044" height="1008" fill="url(#p)"/></svg>`;
fs.writeFileSync(__dirname + '/motif-lylium.svg', tileSvg);

// Maquette du sac
const CUT_Y = 48;                                    // hauteur de la coupe du logo (repère du logo)
const F = { x: 470, y: 400, w: 540, h: 660 };        // face avant
const G = 120, D = 30;                              // soufflet : profondeur, recul perspective                                       // profondeur du soufflet
const bagSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="1600" height="1200" viewBox="0 0 1600 1200">
  <defs>${defsCommon}
    <style>@font-face{font-family:'Great Vibes';src:url(data:font/woff2;base64,${scriptFont}) format('woff2')}</style>
    <radialGradient id="bg" cx=".5" cy=".38" r=".8">
      <stop offset="0" stop-color="#f6f1e8"/><stop offset="1" stop-color="#d9cfbf"/>
    </radialGradient>
    <linearGradient id="lightFront" x1="0" y1="0" x2="1" y2=".3">
      <stop offset="0" stop-color="#fff" stop-opacity=".10"/>
      <stop offset=".55" stop-color="#fff" stop-opacity="0"/>
      <stop offset="1" stop-color="#000" stop-opacity=".22"/>
    </linearGradient>
    <linearGradient id="lightV" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0" stop-color="#000" stop-opacity=".18"/>
      <stop offset=".12" stop-color="#000" stop-opacity="0"/>
      <stop offset=".85" stop-color="#000" stop-opacity="0"/>
      <stop offset="1" stop-color="#000" stop-opacity=".25"/>
    </linearGradient>
    <linearGradient id="ribbon" x1="0" y1="0" x2="1" y2="0">
      <stop offset="0" stop-color="#07061c"/><stop offset=".5" stop-color="#2a2766"/><stop offset="1" stop-color="#07061c"/>
    </linearGradient>
    <linearGradient id="interior" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0" stop-color="#1c1950"/><stop offset="1" stop-color="#03020f"/>
    </linearGradient>
    <linearGradient id="tissue" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="#fbe7bd"/><stop offset=".5" stop-color="#f0cf8a"/><stop offset="1" stop-color="#d9ad5f"/>
    </linearGradient>
    <filter id="blur"><feGaussianBlur stdDeviation="22"/></filter>
    <filter id="soft"><feGaussianBlur stdDeviation="2"/></filter>
    <filter id="emboss" x="-5%" y="-5%" width="110%" height="110%">
      <feDropShadow dx="0" dy="1.5" stdDeviation="1" flood-color="#000" flood-opacity=".55"/>
    </filter>
    <mask id="cut" maskUnits="userSpaceOnUse" x="-400" y="-400" width="800" height="800">
      <rect x="-400" y="-400" width="800" height="800" fill="#fff"/>
      <rect x="-400" y="${CUT_Y - 40}" width="800" height="62" fill="#000"/>
      <text x="0" y="${CUT_Y + 22}" text-anchor="middle" font-family="'Great Vibes', cursive" font-size="132"
            fill="#000" stroke="#000" stroke-width="18" stroke-linejoin="round">Lylium</text>
    </mask>
    <clipPath id="frontClip"><rect x="${F.x}" y="${F.y}" width="${F.w}" height="${F.h}"/></clipPath>
  </defs>

  <rect width="1600" height="1200" fill="url(#bg)"/>
  <!-- ombre au sol -->
  <ellipse cx="${F.x + F.w/2 + 60}" cy="${F.y + F.h + 8}" rx="400" ry="34" fill="#000" opacity=".28" filter="url(#blur)"/>
  <path d="M${F.x+F.w+G-10},${F.y+F.h-D} L${F.x+F.w+G+140},${F.y+F.h+10} L${F.x+F.w},${F.y+F.h+12}Z" fill="#000" opacity=".12" filter="url(#blur)"/>

  <!-- anse arrière (fixée sur le panneau arrière) -->
  <path d="M${F.x+175+G},${F.y-D+10} C${F.x+175+G},${F.y-D-250} ${F.x+F.w-175+G},${F.y-D-250} ${F.x+F.w-175+G},${F.y-D+10}"
        fill="none" stroke="#06051a" stroke-width="15" stroke-linecap="round"/>
  <!-- ouverture du sac : intérieur + papier de soie -->
  <path d="M${F.x},${F.y} L${F.x+G},${F.y-D} L${F.x+F.w+G},${F.y-D} L${F.x+F.w},${F.y}Z" fill="#05041a"/>
  <path d="M${F.x+G},${F.y-D} L${F.x+F.w+G},${F.y-D} L${F.x+F.w},${F.y} L${F.x},${F.y}Z" fill="url(#interior)"/>
  <g filter="url(#emboss)">
    <path d="M${F.x+60},${F.y} L${F.x+150},${F.y-120} L${F.x+215},${F.y-55} L${F.x+300},${F.y-150} L${F.x+330},${F.y-40} L${F.x+420},${F.y-105} L${F.x+430},${F.y}Z" fill="url(#tissue)"/>
    <path d="M${F.x+300},${F.y-150} L${F.x+280},${F.y}" stroke="#b98a3e" stroke-opacity=".35" stroke-width="1.5"/>
    <path d="M${F.x+150},${F.y-120} L${F.x+175},${F.y}" stroke="#b98a3e" stroke-opacity=".35" stroke-width="1.5"/>
  </g>
  <!-- soufflet latéral -->
  <g>
    <path d="M${F.x+F.w},${F.y} L${F.x+F.w+G},${F.y-D} L${F.x+F.w+G},${F.y+F.h-D} L${F.x+F.w},${F.y+F.h}Z" fill="${NAVY}"/>
    <path d="M${F.x+F.w},${F.y} L${F.x+F.w+G},${F.y-D} L${F.x+F.w+G},${F.y+F.h-D} L${F.x+F.w},${F.y+F.h}Z" fill="#000" opacity=".42"/>
    <path d="M${F.x+F.w},${F.y} L${F.x+F.w+G/2},${F.y+40} L${F.x+F.w+G},${F.y-D}" fill="#000" opacity=".35"/>
    <path d="M${F.x+F.w+G/2},${F.y+40} L${F.x+F.w+G/2},${F.y+F.h-80}" stroke="#000" stroke-opacity=".35" stroke-width="2"/>
    <path d="M${F.x+F.w},${F.y+F.h} L${F.x+F.w+G/2},${F.y+F.h-80} L${F.x+F.w+G},${F.y+F.h-D}" fill="none" stroke="#000" stroke-opacity=".35" stroke-width="2"/>
    
  </g>

  <!-- face avant -->
  <g clip-path="url(#frontClip)">
    <rect x="${F.x}" y="${F.y}" width="${F.w}" height="${F.h}" fill="${NAVY}"/>

    <!-- logo unique : fleur de lys coupée en deux par « Lylium » en calligraphie -->
    <g transform="translate(${F.x + F.w/2} ${F.y + F.h/2 - 20})">
      <g filter="url(#emboss)"><g mask="url(#cut)"><use href="#fleur" x="-160" y="-160" width="320" height="320" fill="url(#gold)"/></g></g>
      <text x="0" y="${CUT_Y + 22}" text-anchor="middle" font-family="'Great Vibes', cursive" font-size="132"
            fill="url(#gold)" filter="url(#emboss)">Lylium</text>
    </g>

    <rect x="${F.x}" y="${F.y}" width="${F.w}" height="${F.h}" fill="url(#lightFront)"/>
    <rect x="${F.x}" y="${F.y}" width="${F.w}" height="${F.h}" fill="url(#lightV)"/>
  </g>
  <rect x="${F.x}" y="${F.y}" width="${F.w}" height="${F.h}" fill="none" stroke="#000" stroke-opacity=".4"/>

  <!-- anse avant : ruban satin + œillets dorés -->
  <path d="M${F.x+175},${F.y+24} C${F.x+175},${F.y-240} ${F.x+F.w-175},${F.y-240} ${F.x+F.w-175},${F.y+24}"
        fill="none" stroke="#000" stroke-opacity=".3" stroke-width="18" stroke-linecap="round" filter="url(#soft)" transform="translate(4 6)"/>
  <path d="M${F.x+175},${F.y+24} C${F.x+175},${F.y-240} ${F.x+F.w-175},${F.y-240} ${F.x+F.w-175},${F.y+24}"
        fill="none" stroke="${NAVY}" stroke-width="16" stroke-linecap="round"/>
  <path d="M${F.x+175},${F.y+24} C${F.x+175},${F.y-240} ${F.x+F.w-175},${F.y-240} ${F.x+F.w-175},${F.y+24}"
        fill="none" stroke="#3a3690" stroke-opacity=".7" stroke-width="3" transform="translate(-3 -2)"/>
  ${[F.x+175, F.x+F.w-175].map(cx => `
  <circle cx="${cx}" cy="${F.y+24}" r="13" fill="url(#gold)"/>
  <circle cx="${cx}" cy="${F.y+24}" r="7.5" fill="#05041a"/>
  <circle cx="${cx}" cy="${F.y+24}" r="13" fill="none" stroke="${GOLD_DEEP}" stroke-width="1"/>`).join('')}

  <!-- légende -->
  <g font-family="'Cormorant Garamond','Liberation Serif',serif" fill="${NAVY}">
    <text x="88" y="122" font-family="'Great Vibes', cursive" font-size="64">Lylium</text>
    <text x="92" y="178" font-family="'Inter','DejaVu Sans',sans-serif" font-size="13" letter-spacing="4" opacity=".7">SAC SHOPPING PREMIUM — BLEU NUIT, LOGO FLEUR DE LYS</text>
    <g font-family="'Inter','DejaVu Sans',sans-serif" font-size="13" letter-spacing="1.5">
      <rect x="90" y="1050" width="34" height="34" fill="${NAVY}"/><text x="134" y="1072">Bleu nuit #0E0C32</text>
      <rect x="300" y="1050" width="34" height="34" fill="${GOLD}"/><text x="344" y="1072">Or #F7BB57 (dorure à chaud)</text>
    </g>
  </g>
</svg>`;
fs.writeFileSync(__dirname + '/sac-lylium.svg', bagSvg);

(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1600, height: 1200 } });
  const fonts = `<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=Inter:wght@500&display=swap" rel="stylesheet">`;
  for (const [svg, out, w, h] of [[bagSvg, 'sac-lylium.png', 1600, 1200], [tileSvg, 'motif-lylium.png', 1044, 1008]]) {
    await page.setViewportSize({ width: w, height: h });
    await page.setContent(`<html><head>${fonts}<style>body{margin:0}</style></head><body>${svg}</body></html>`, { waitUntil: 'networkidle' }).catch(() => {});
    await page.evaluate(() => document.fonts.ready);
    await page.screenshot({ path: __dirname + '/' + out });
  }
  await browser.close();
})();
