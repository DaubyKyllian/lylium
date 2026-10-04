// Boîte rigide Lylium : bleu nuit, logo en dorure au centre du couvercle.
const { logoMark, NAVY } = require('./build.js');
const { sceneSvg, render, CX } = require('./pub.js');
const TOP = 800;                                 // dessus du socle pour cette scène

// Projection axonométrique : x vers la droite, z en profondeur, y vers le haut
const ex = [0.94, 0.1], ez = [0.3, -0.42];
const BW = 680, BD = 470, BH = 200;              // largeur, profondeur, hauteur totale
const LID = 70, OV = 5;                          // hauteur du couvercle, débord
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
</defs>`;

const faces = (b, grain = true) => `
  <path d="${b.front}" fill="url(#bxFront)"/>
  <path d="${b.right}" fill="url(#bxRight)"/>
  ${b.top ? `<path d="${b.top}" fill="url(#bxTop)"/>` : ''}
  ${grain ? `<path d="${b.front}" fill="#fff" filter="url(#grain)"/><path d="${b.right}" fill="#fff" filter="url(#grain)"/>` : ''}`;

// Ombre portée du couvercle sur la base
const yS = BH - LID;
const lidShade = `<path d="${poly([P(0, yS, 0), P(BW, yS, 0), P(BW, yS - 18, 0), P(0, yS - 18, 0)])}" fill="url(#lidShadow)"/>
  <path d="${poly([P(BW, yS, 0), P(BW, yS, BD), P(BW, yS - 18, BD), P(BW, yS - 18, 0)])}" fill="#000" opacity=".35"/>`;

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
  <g transform="${logoT}"><g transform="scale(1.25)">${logoMark}</g></g>
</g>`;

const foot = [P(0, 0, 0), P(BW, 0, 0), P(BW, 0, BD), P(0, 0, BD)];
const shadow = `<path d="${poly(foot.map(([x, y]) => [x + 14, y + 8]))}" fill="#000" opacity=".8" filter="url(#blur)"/>`;

render(sceneSvg(box, shadow, { ry: 165, reflect: false, top: TOP }), 'boite-lylium');
