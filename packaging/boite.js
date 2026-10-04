// Boîte rigide Lylium : bleu nuit, fleur de lys seule en dorure au centre du couvercle.
const { logoFleur, NAVY } = require('./build.js');
const { sceneSvg, render, CX } = require('./pub.js');
const TOP = 730;                                 // dessus du socle pour cette scène

// Projection axonométrique : x vers la droite, z en profondeur, y vers le haut
const ex = [0.94, 0.1], ez = [0.3, -0.42];
const BW = 600, BD = 430, BH = 100;              // largeur, profondeur, hauteur totale (format plat)
const LID = BH - 13, OV = 3;                     // couvercle quasi pleine hauteur, débord
const RX = BW * 0.11, RW = 30;                    // ruban : position et largeur
const FLOOR = TOP + 30;                          // altitude écran du centre de l'empreinte
const O = [CX - (BW / 2) * ex[0] - (BD / 2) * ez[0], FLOOR - (BW / 2) * ex[1] - (BD / 2) * ez[1]];
const P = (x, y, z) => [O[0] + x * ex[0] + z * ez[0], O[1] + x * ex[1] + z * ez[1] - y];
const poly = pts => 'M' + pts.map(p => p.map(n => n.toFixed(1)).join(',')).join(' L') + 'Z';

// Un pavé : faces avant (z=z0), droite (x=x1), dessus (y=y1)
const block = (x0, x1, y0, y1, z0, z1, id) => ({
  front: poly([P(x0, y0, z0), P(x1, y0, z0), P(x1, y1, z0), P(x0, y1, z0)]),
  right: poly([P(x1, y0, z0), P(x1, y0, z1), P(x1, y1, z1), P(x1, y1, z0)]),
  top: poly([P(x0, y1, z0), P(x1, y1, z0), P(x1, y1, z1), P(x0, y1, z1)]),
});
const base = block(0, BW, 0, BH - LID + 4, 0, BD);
const lid = block(-OV, BW + OV, BH - LID, BH, -OV, BD + OV);

const top = P(BW / 2, BH, BD / 2);
const logoT = `matrix(${ex[0]} ${ex[1]} ${-ez[0]} ${-ez[1]} ${top[0].toFixed(1)} ${top[1].toFixed(1)})`;
const edge = (a, b, c, o, w = 1.5) => `<path d="M${a.map(n => n.toFixed(1))} L${b.map(n => n.toFixed(1))}" stroke="${c}" stroke-opacity="${o}" stroke-width="${w}" stroke-linecap="round"/>`;

const defs = `<defs>
  <linearGradient id="bxFront" x1="0" y1="0" x2="1" y2="0">
    <stop offset="0" stop-color="#1d1a52"/><stop offset=".6" stop-color="${NAVY}"/><stop offset="1" stop-color="#0a0926"/>
  </linearGradient>
  <linearGradient id="bxRight" x1="0" y1="0" x2="1" y2="0">
    <stop offset="0" stop-color="#090820"/><stop offset="1" stop-color="#040312"/>
  </linearGradient>
  <linearGradient id="bxTop" x1="0" y1="1" x2="1" y2="0">
    <stop offset="0" stop-color="#1a174c"/><stop offset=".45" stop-color="#2a2672"/><stop offset="1" stop-color="#131140"/>
  </linearGradient>
  <linearGradient id="lidShadow" x1="0" y1="0" x2="0" y2="1">
    <stop offset="0" stop-color="#000" stop-opacity=".65"/><stop offset="1" stop-color="#000" stop-opacity="0"/>
  </linearGradient>
  <linearGradient id="satin" x1="0" y1="0" x2="1" y2="0">
    <stop offset="0" stop-color="#9c7230"/><stop offset=".3" stop-color="#e9c47c"/><stop offset=".5" stop-color="#fff0c8"/>
    <stop offset=".7" stop-color="#d9ac5c"/><stop offset="1" stop-color="#8a6226"/>
  </linearGradient>
  <linearGradient id="satinV" x1="0" y1="0" x2="0" y2="1">
    <stop offset="0" stop-color="#e4bd72"/><stop offset=".6" stop-color="#b98a3e"/><stop offset="1" stop-color="#7a5520"/>
  </linearGradient>
  <filter id="ribbonShadow" x="-20%" y="-20%" width="140%" height="140%">
    <feDropShadow dx="1.5" dy="2.5" stdDeviation="2" flood-color="#000" flood-opacity=".55"/>
  </filter>
</defs>`;

const faces = (b, grain = true) => `
  <path d="${b.front}" fill="url(#bxFront)"/>
  <path d="${b.right}" fill="url(#bxRight)"/>
  ${b.top ? `<path d="${b.top}" fill="url(#bxTop)"/>` : ''}
  ${grain ? `<path d="${b.front}" fill="#fff" filter="url(#grain)"/><path d="${b.right}" fill="#fff" filter="url(#grain)"/>` : ''}`;

// Ombre portée du couvercle sur la base
const yS = BH - LID;
const lidShade = `<path d="${poly([P(0, yS, 0), P(BW, yS, 0), P(BW, yS - 8, 0), P(0, yS - 8, 0)])}" fill="url(#lidShadow)"/>
  <path d="${poly([P(BW, yS, 0), P(BW, yS, BD), P(BW, yS - 8, BD), P(BW, yS - 8, 0)])}" fill="#000" opacity=".35"/>`;


// Ruban satiné : fait le tour de la boîte dans le sens de la profondeur, noué à plat sur le dessus
const ribbonTop = poly([P(RX, BH + .5, -OV), P(RX + RW, BH + .5, -OV), P(RX + RW, BH + .5, BD + OV), P(RX, BH + .5, BD + OV)]);
const ribbonFront = poly([P(RX, 0, -OV - .5), P(RX + RW, 0, -OV - .5), P(RX + RW, BH, -OV - .5), P(RX, BH, -OV - .5)]);
const knot = P(RX + RW / 2, BH, BD * 0.42);
const knotT = `matrix(${ex[0]} ${ex[1]} ${-ez[0]} ${-ez[1]} ${knot[0].toFixed(1)} ${knot[1].toFixed(1)})`;
// Nœud dessiné dans le plan du couvercle (repère local : x à droite, y vers l'avant)
const bow = `<g filter="url(#ribbonShadow)">
  <path d="M-6,8 C-30,40 -44,70 -58,96 L-34,100 C-26,74 -14,44 4,12Z" fill="url(#satinV)"/>
  <path d="M6,8 C22,44 30,76 30,104 L54,96 C42,68 26,40 8,6Z" fill="url(#satinV)"/>
  <path d="M-4,-2 C-40,-46 -110,-40 -104,-6 C-100,22 -40,22 -4,6Z" fill="url(#satin)"/>
  <path d="M4,-2 C40,-46 110,-40 104,-6 C100,22 40,22 4,6Z" fill="url(#satin)"/>
  <path d="M-10,-2 C-40,-24 -84,-24 -86,-6 C-84,8 -40,8 -10,4Z" fill="#000" opacity=".22"/>
  <path d="M10,-2 C40,-24 84,-24 86,-6 C84,8 40,8 10,4Z" fill="#000" opacity=".22"/>
  <rect x="-15" y="-13" width="30" height="24" rx="6" fill="url(#satin)"/>
  <path d="M-15,-4 L15,-4" stroke="#fff6dc" stroke-opacity=".6" stroke-width="2"/>
</g>`;
const ribbon = `
  <path d="${ribbonFront}" fill="url(#satin)"/>
  <path d="${ribbonFront}" fill="#000" opacity=".18"/>
  <path d="${ribbonTop}" fill="url(#satin)"/>
  <path d="${ribbonTop}" fill="#fff" opacity=".06"/>
  <g transform="${knotT}"><g transform="scale(1.1)">${bow}</g></g>`;

const box = `<g>${defs}
  ${faces({ front: base.front, right: base.right })}
  ${lidShade}
  ${faces(lid)}
  <path d="${lid.top}" fill="#fff" filter="url(#grain)"/>
  <!-- arêtes adoucies -->
  ${edge(P(-OV, BH, -OV), P(BW + OV, BH, -OV), '#8f8ae8', .45, 2)}
  ${edge(P(BW + OV, BH, -OV), P(BW + OV, BH, BD + OV), '#8f8ae8', .25, 1.5)}
  ${edge(P(-OV, BH, -OV), P(-OV, BH, BD + OV), '#8f8ae8', .3, 1.5)}
  ${edge(P(BW + OV, BH, -OV), P(BW + OV, BH - LID, -OV), '#8f8ae8', .25, 1.5)}
  ${edge(P(BW, 0, 0), P(BW, yS, 0), '#8f8ae8', .15, 1.2)}
  ${edge(P(-OV, BH - LID, -OV), P(BW + OV, BH - LID, -OV), '#000', .5, 1.5)}
  <!-- logo en dorure sur le couvercle -->
  <g transform="${logoT}"><g transform="scale(.85)">${logoFleur}</g></g>
  ${ribbon}
</g>`;

const foot = [P(0, 0, 0), P(BW, 0, 0), P(BW, 0, BD), P(0, 0, BD)];
const shadow = `<path d="${poly(foot.map(([x, y]) => [x + 14, y + 8]))}" fill="#000" opacity=".8" filter="url(#blur)"/>`;

render(sceneSvg(box, shadow, { ry: 165, reflect: false, top: TOP }), 'boite-lylium');
