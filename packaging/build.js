// Génère le motif et la maquette du sac Lylium, puis les rend en PNG.
const fs = require('fs');
const { chromium } = require('playwright');

const NAVY = '#0e0c32', GOLD = '#f7bb57', GOLD_DEEP = '#c98f35';
const motifs = fs.readFileSync(__dirname + '/motifs.svgfrag', 'utf8');
const scriptFont = fs.readFileSync(__dirname + '/fonts/great-vibes-latin-400-normal.woff2').toString('base64');
const logoPath = fs.readFileSync(__dirname + '/logo-fleur.svg', 'utf8').match(/ d="([^"]+)"/)[1];
const logoSymbol = `<symbol id="logo" viewBox="0 0 2004 1820"><path fill-rule="evenodd" d="${logoPath}"/></symbol>`;
const tile = fs.readFileSync(__dirname + '/tile.svgfrag', 'utf8');

const defsCommon = `
  ${motifs}
  ${logoSymbol}
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
const CUT_Y = 40;                                    // hauteur de la coupe du logo (repère du logo)
const LOGO_W = 340, LOGO_H = LOGO_W * 1820 / 2004;  // taille du logo sur la face
const F = { x: 470, y: 400, w: 540, h: 660 };        // face avant
const G = 120, D = 30;                              // soufflet : profondeur, recul perspective

// Logo : fleur de lys coupée par « Lylium », centré sur l'origine
const logoMark = `<g filter="url(#foil)"><g mask="url(#cut)"><use href="#logo" x="-${LOGO_W/2}" y="-${LOGO_H/2}" width="${LOGO_W}" height="${LOGO_H}" fill="url(#gold)"/></g></g>
      <text x="0" y="${CUT_Y + 22}" text-anchor="middle" font-family="'Great Vibes', cursive" font-size="132"
            fill="url(#gold)" filter="url(#foil)">Lylium</text>`;
// Logo seul (fleur de lys sans le nom), centré sur l'origine
const logoFleur = `<g filter="url(#foil)"><use href="#logo" x="-${LOGO_W/2}" y="-${LOGO_H/2}" width="${LOGO_W}" height="${LOGO_H}" fill="url(#gold)"/></g>`;

// Dessin du sac (sans décor), réutilisé par la maquette et le visuel pub
const bagDefs = `${defsCommon}
    <style>@font-face{font-family:'Great Vibes';src:url(data:font/woff2;base64,${scriptFont}) format('woff2')}</style>
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
    <linearGradient id="tissueBack" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="#e6c98d"/><stop offset="1" stop-color="#b98d44"/>
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
    <!-- dorure à chaud : relief + reflet métallique -->
    <filter id="foil" x="-10%" y="-10%" width="120%" height="120%">
      <feGaussianBlur in="SourceAlpha" stdDeviation="1.4" result="bump"/>
      <feSpecularLighting in="bump" surfaceScale="2.2" specularConstant="1.1" specularExponent="22" lighting-color="#fff4d8" result="spec">
        <feDistantLight azimuth="235" elevation="38"/>
      </feSpecularLighting>
      <feComposite in="spec" in2="SourceAlpha" operator="in" result="specIn"/>
      <feComposite in="SourceGraphic" in2="specIn" operator="arithmetic" k2="1" k3=".75" result="lit"/>
      <feDropShadow in="lit" dx="0" dy="1.2" stdDeviation=".8" flood-color="#000" flood-opacity=".6"/>
    </filter>
    <!-- grain du papier -->
    <filter id="grain" x="0" y="0" width="100%" height="100%">
      <feTurbulence type="fractalNoise" baseFrequency=".9" numOctaves="2" seed="3"/>
      <feColorMatrix values="0 0 0 0 1  0 0 0 0 1  0 0 0 0 1  0 0 0 .05 0"/>
      <feComposite in2="SourceGraphic" operator="in"/>
    </filter>
    <linearGradient id="gussetShade" x1="0" y1="0" x2="1" y2="0">
      <stop offset="0" stop-color="#000" stop-opacity=".62"/><stop offset=".5" stop-color="#000" stop-opacity=".4"/>
      <stop offset=".52" stop-color="#000" stop-opacity=".55"/><stop offset="1" stop-color="#000" stop-opacity=".35"/>
    </linearGradient>
    <linearGradient id="ao" x1="0" y1="0" x2="0" y2="1">
      <stop offset=".88" stop-color="#000" stop-opacity="0"/><stop offset="1" stop-color="#000" stop-opacity=".22"/>
    </linearGradient>
    <clipPath id="frontClip"><rect x="${F.x}" y="${F.y}" width="${F.w}" height="${F.h}"/></clipPath>`;
const bagBody = `
  <!-- anse arrière (fixée sur le panneau arrière) -->
  <path d="M${F.x+175+G},${F.y-D+10} C${F.x+175+G},${F.y-D-250} ${F.x+F.w-175+G},${F.y-D-250} ${F.x+F.w-175+G},${F.y-D+10}" fill="none" stroke="#06051a" stroke-width="15" stroke-linecap="round"/>
  <path d="M${F.x+175+G},${F.y-D+10} C${F.x+175+G},${F.y-D-250} ${F.x+F.w-175+G},${F.y-D-250} ${F.x+F.w-175+G},${F.y-D+10}" fill="none" stroke="#1c1a52" stroke-opacity=".8" stroke-width="13" stroke-dasharray="2.5 4.5" stroke-linecap="butt"/>
  <path d="M${F.x+175+G},${F.y-D+10} C${F.x+175+G},${F.y-D-250} ${F.x+F.w-175+G},${F.y-D-250} ${F.x+F.w-175+G},${F.y-D+10}" fill="none" stroke="#8a86e0" stroke-opacity=".25" stroke-width="3" transform="translate(-3 -2)"/>

  <!-- ouverture du sac : intérieur + papier de soie -->
  <path d="M${F.x},${F.y} L${F.x+G},${F.y-D} L${F.x+F.w+G},${F.y-D} L${F.x+F.w},${F.y}Z" fill="#05041a"/>
  <path d="M${F.x+G},${F.y-D} L${F.x+F.w+G},${F.y-D} L${F.x+F.w},${F.y} L${F.x},${F.y}Z" fill="url(#interior)"/>
  <!-- papier de soie froissé -->
  <g>
    <path d="M${F.x+40},${F.y+0} C${F.x+70},${F.y-60} ${F.x+110},${F.y-110} ${F.x+165},${F.y-128} C${F.x+175},${F.y-95} ${F.x+190},${F.y-80} ${F.x+215},${F.y-72} C${F.x+240},${F.y-120} ${F.x+280},${F.y-160} ${F.x+330},${F.y-168} C${F.x+338},${F.y-120} ${F.x+360},${F.y-95} ${F.x+400},${F.y-90} C${F.x+420},${F.y-60} ${F.x+440},${F.y-25} ${F.x+455},${F.y+0} Z" fill="url(#tissueBack)" filter="url(#emboss)"/>
    <path d="M${F.x+40},${F.y+0} C${F.x+70},${F.y-60} ${F.x+110},${F.y-110} ${F.x+165},${F.y-128} C${F.x+175},${F.y-95} ${F.x+190},${F.y-80} ${F.x+215},${F.y-72} C${F.x+240},${F.y-120} ${F.x+280},${F.y-160} ${F.x+330},${F.y-168} C${F.x+338},${F.y-120} ${F.x+360},${F.y-95} ${F.x+400},${F.y-90} C${F.x+420},${F.y-60} ${F.x+440},${F.y-25} ${F.x+455},${F.y+0} Z" fill="#000" filter="url(#grain)" opacity=".8"/>
    <path d="M${F.x+70},${F.y+0} C${F.x+95},${F.y-55} ${F.x+125},${F.y-95} ${F.x+170},${F.y-118} C${F.x+178},${F.y-80} ${F.x+200},${F.y-50} ${F.x+235},${F.y-40} C${F.x+250},${F.y-20} ${F.x+262},${F.y-8} ${F.x+270},${F.y+0} Z" fill="url(#tissue)" filter="url(#emboss)"/>
    <path d="M${F.x+70},${F.y+0} C${F.x+95},${F.y-55} ${F.x+125},${F.y-95} ${F.x+170},${F.y-118} C${F.x+178},${F.y-80} ${F.x+200},${F.y-50} ${F.x+235},${F.y-40} C${F.x+250},${F.y-20} ${F.x+262},${F.y-8} ${F.x+270},${F.y+0} Z" fill="#000" filter="url(#grain)" opacity=".8"/>
    <path d="M${F.x+230},${F.y+0} C${F.x+250},${F.y-60} ${F.x+280},${F.y-118} ${F.x+322},${F.y-150} C${F.x+330},${F.y-110} ${F.x+345},${F.y-80} ${F.x+372},${F.y-62} C${F.x+395},${F.y-50} ${F.x+420},${F.y-28} ${F.x+432},${F.y+0} Z" fill="url(#tissue)" filter="url(#emboss)"/>
    <path d="M${F.x+230},${F.y+0} C${F.x+250},${F.y-60} ${F.x+280},${F.y-118} ${F.x+322},${F.y-150} C${F.x+330},${F.y-110} ${F.x+345},${F.y-80} ${F.x+372},${F.y-62} C${F.x+395},${F.y-50} ${F.x+420},${F.y-28} ${F.x+432},${F.y+0} Z" fill="#000" filter="url(#grain)" opacity=".8"/>
    <path d="M${F.x+165},${F.y-128} Q${F.x+165},${F.y-64} ${F.x+150},${F.y+0}" fill="none" stroke="#fff" stroke-opacity="0.35" stroke-width="1.6" stroke-linecap="round"/>
    <path d="M${F.x+170},${F.y-118} Q${F.x+185},${F.y-69} ${F.x+185},${F.y-20}" fill="none" stroke="#a67a33" stroke-opacity="0.35" stroke-width="1.6" stroke-linecap="round"/>
    <path d="M${F.x+322},${F.y-150} Q${F.x+319},${F.y-80} ${F.x+300},${F.y-10}" fill="none" stroke="#fff" stroke-opacity="0.4" stroke-width="1.6" stroke-linecap="round"/>
    <path d="M${F.x+330},${F.y-110} Q${F.x+353},${F.y-65} ${F.x+360},${F.y-20}" fill="none" stroke="#a67a33" stroke-opacity="0.3" stroke-width="1.6" stroke-linecap="round"/>
    <path d="M${F.x+215},${F.y-72} Q${F.x+228},${F.y-39} ${F.x+225},${F.y-5}" fill="none" stroke="#a67a33" stroke-opacity="0.25" stroke-width="1.6" stroke-linecap="round"/>
  </g>
  <!-- soufflet latéral -->
  <g>
    <path d="M${F.x+F.w},${F.y} L${F.x+F.w+G},${F.y-D} L${F.x+F.w+G},${F.y+F.h-D} L${F.x+F.w},${F.y+F.h}Z" fill="${NAVY}"/>
    <path d="M${F.x+F.w},${F.y} L${F.x+F.w+G},${F.y-D} L${F.x+F.w+G},${F.y+F.h-D} L${F.x+F.w},${F.y+F.h}Z" fill="url(#gussetShade)"/>
    <path d="M${F.x+F.w},${F.y} L${F.x+F.w+G},${F.y-D} L${F.x+F.w+G},${F.y+F.h-D} L${F.x+F.w},${F.y+F.h}Z" fill="#fff" filter="url(#grain)"/>
    <path d="M${F.x+F.w},${F.y} L${F.x+F.w+G/2},${F.y+40} L${F.x+F.w+G},${F.y-D}" fill="#000" opacity=".35"/>
    <path d="M${F.x+F.w+G/2},${F.y+40} L${F.x+F.w+G/2},${F.y+F.h-80}" stroke="#000" stroke-opacity=".35" stroke-width="2"/>
    <path d="M${F.x+F.w},${F.y+F.h} L${F.x+F.w+G/2},${F.y+F.h-80} L${F.x+F.w+G},${F.y+F.h-D}" fill="none" stroke="#000" stroke-opacity=".35" stroke-width="2"/>
    
  </g>

  <!-- face avant -->
  <g clip-path="url(#frontClip)">
    <rect x="${F.x}" y="${F.y}" width="${F.w}" height="${F.h}" fill="${NAVY}"/>

    <!-- logo unique : fleur de lys coupée en deux par « Lylium » en calligraphie -->
    <g transform="translate(${F.x + F.w/2} ${F.y + F.h/2 - 20})">
${logoMark}
    </g>

    <rect x="${F.x}" y="${F.y}" width="${F.w}" height="${F.h}" fill="url(#lightFront)"/>
    <rect x="${F.x}" y="${F.y}" width="${F.w}" height="${F.h}" fill="url(#lightV)"/>
    <rect x="${F.x}" y="${F.y}" width="${F.w}" height="${F.h}" fill="url(#ao)"/>
    <rect x="${F.x}" y="${F.y}" width="${F.w}" height="${F.h}" fill="#fff" filter="url(#grain)"/>
    <!-- pliure du fond (plis à 45° visibles en bas de face) -->
    <path d="M${F.x},${F.y+F.h-70} L${F.x+F.w},${F.y+F.h-70}" stroke="#fff" stroke-opacity=".05" stroke-width="2"/>
    <path d="M${F.x},${F.y+F.h-68} L${F.x+F.w},${F.y+F.h-68}" stroke="#000" stroke-opacity=".12" stroke-width="1.5"/>
  </g>
  <line x1="${F.x+1}" y1="${F.y}" x2="${F.x+1}" y2="${F.y+F.h}" stroke="#fff" stroke-opacity=".12" stroke-width="2"/>
  <line x1="${F.x}" y1="${F.y+1}" x2="${F.x+F.w}" y2="${F.y+1}" stroke="#fff" stroke-opacity=".18" stroke-width="1.5"/>
  <line x1="${F.x+F.w}" y1="${F.y}" x2="${F.x+F.w}" y2="${F.y+F.h}" stroke="#7d78c8" stroke-opacity=".25" stroke-width="1.5"/>
  <rect x="${F.x}" y="${F.y}" width="${F.w}" height="${F.h}" fill="none" stroke="#000" stroke-opacity=".4"/>

  <!-- anse avant : ruban satin + œillets dorés -->
  <path d="M${F.x+175},${F.y+24} C${F.x+175},${F.y-240} ${F.x+F.w-175},${F.y-240} ${F.x+F.w-175},${F.y+24}"
        fill="none" stroke="#000" stroke-opacity=".3" stroke-width="18" stroke-linecap="round" filter="url(#soft)" transform="translate(4 6)"/>
  <path d="M${F.x+175},${F.y+24} C${F.x+175},${F.y-240} ${F.x+F.w-175},${F.y-240} ${F.x+F.w-175},${F.y+24}" fill="none" stroke="${NAVY}" stroke-width="15" stroke-linecap="round"/>
  <path d="M${F.x+175},${F.y+24} C${F.x+175},${F.y-240} ${F.x+F.w-175},${F.y-240} ${F.x+F.w-175},${F.y+24}" fill="none" stroke="#3b3790" stroke-opacity=".75" stroke-width="13" stroke-dasharray="2.5 4.5" stroke-linecap="butt"/>
  <path d="M${F.x+175},${F.y+24} C${F.x+175},${F.y-240} ${F.x+F.w-175},${F.y-240} ${F.x+F.w-175},${F.y+24}" fill="none" stroke="#8a86e0" stroke-opacity=".25" stroke-width="3" transform="translate(-3 -2)"/>
  ${[F.x+175, F.x+F.w-175].map(cx => `
  <circle cx="${cx}" cy="${F.y+24}" r="13" fill="url(#gold)"/>
  <circle cx="${cx}" cy="${F.y+24}" r="7.5" fill="#05041a"/>
  <circle cx="${cx}" cy="${F.y+24}" r="13" fill="none" stroke="${GOLD_DEEP}" stroke-width="1"/>`).join('')}`;
module.exports = { logoMark, logoFleur, bagDefs, bagBody, F, G, D, NAVY, GOLD, GOLD_DEEP, motifs, tile };

const bagSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="1600" height="1200" viewBox="0 0 1600 1200">
  <defs>${bagDefs}
    <radialGradient id="bg" cx=".5" cy=".38" r=".8">
      <stop offset="0" stop-color="#f6f1e8"/><stop offset="1" stop-color="#d9cfbf"/>
    </radialGradient>
  </defs>
  <rect width="1600" height="1200" fill="url(#bg)"/>
  <!-- ombre au sol -->
  <ellipse cx="${F.x + F.w/2 + 60}" cy="${F.y + F.h + 8}" rx="400" ry="34" fill="#000" opacity=".28" filter="url(#blur)"/>
  <path d="M${F.x+F.w+G-10},${F.y+F.h-D} L${F.x+F.w+G+140},${F.y+F.h+10} L${F.x+F.w},${F.y+F.h+12}Z" fill="#000" opacity=".12" filter="url(#blur)"/>
${bagBody}

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

if (require.main === module) (async () => {
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
