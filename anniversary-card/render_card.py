#!/usr/bin/env python3
"""Gilded Orbit — 24th anniversary card for Rob & Rae Lee.
5x7in card, 600dpi. Page 1: cover (night chart, 24 rings, two lights).
Page 2: interior message on cream.
"""
import math, random
from PIL import Image, ImageDraw, ImageFont, ImageFilter

random.seed(24)

W, H = 3000, 4200            # 5x7 in @ 600 dpi
FONTS = "/root/.claude/skills/canvas-design/canvas-fonts"

INK    = (14, 27, 36)        # deep petrol night
INK_HI = (20, 37, 48)
GOLD   = (201, 162, 75)
GOLD_PALE = (230, 200, 127)
CREAM  = (244, 238, 225)
CREAM_INK = (24, 36, 44)

def F(name, size):
    return ImageFont.truetype(f"{FONTS}/{name}", size)

def spaced_text(draw, xy, text, font, fill, tracking=0, anchor="mm"):
    """Draw text with letter-spacing, centered on xy if anchor='mm'."""
    widths = []
    for ch in text:
        b = draw.textbbox((0, 0), ch, font=font)
        widths.append(b[2] - b[0] if ch != " " else font.size * 0.42)
    total = sum(widths) + tracking * (len(text) - 1)
    asc, desc = font.getmetrics()
    x = xy[0] - total / 2 if anchor[0] == "m" else xy[0]
    y = xy[1]
    for ch, w in zip(text, widths):
        if ch != " ":
            draw.text((x, y), ch, font=font, fill=fill, anchor="lm")
        x += w + tracking
    return total

# ----------------------------------------------------------------- PAGE 1
img = Image.new("RGB", (W, H), INK)

# vertical glow gradient toward the ring center (subtle, hand-lit feel)
grad = Image.new("L", (1, H), 0)
for y in range(H):
    d = abs(y - int(H * 0.44)) / (H * 0.75)
    grad.putpixel((0, y), max(0, int(26 * (1 - d))))
grad = grad.resize((W, H))
img = Image.composite(Image.new("RGB", (W, H), INK_HI), img, grad)

base = img.convert("RGBA")

# faint star field (kept away from the ring heart)
stars = Image.new("RGBA", (W, H), (0, 0, 0, 0))
sd = ImageDraw.Draw(stars)
cx, cy = W // 2, int(H * 0.44)
for _ in range(210):
    x, y = random.uniform(60, W - 60), random.uniform(60, H * 0.78)
    if math.hypot(x - cx, y - cy) < 380:
        continue
    r = random.uniform(0.8, 2.2)
    a = random.randint(18, 70)
    sd.ellipse([x - r, y - r, x + r, y + r], fill=(*GOLD_PALE, a))
base = Image.alpha_composite(base, stars)

# ---- 24 concentric hand-wavering rings -------------------------------
rings = Image.new("RGBA", (W, H), (0, 0, 0, 0))
rd = ImageDraw.Draw(rings)
N = 24
r0, r1 = 96, 1128
radii = [r0 + (r1 - r0) * i / (N - 1) for i in range(N)]

for i, r in enumerate(radii):
    milestone = (i + 1) % 6 == 0
    phases = [random.uniform(0, 2 * math.pi) for _ in range(3)]
    amp = random.uniform(1.2, 2.6)
    alpha = 150 if milestone else random.randint(64, 108)
    col = GOLD_PALE if milestone else GOLD
    width = 4 if milestone else 2
    pts = []
    for k in range(721):
        t = 2 * math.pi * k / 720
        wob = (math.sin(3 * t + phases[0]) + 0.6 * math.sin(7 * t + phases[1])
               + 0.4 * math.sin(13 * t + phases[2])) * amp
        pts.append((cx + (r + wob) * math.cos(t), cy + (r + wob) * math.sin(t)))
    rd.line(pts, fill=(*col, alpha), width=width, joint="curve")

# meridian: hairline + a tick per year below center, numerals at milestones
mer = ImageDraw.Draw(rings)
mer.line([(cx, cy - r1 - 46), (cx, cy + r1 + 46)], fill=(*GOLD, 34), width=2)
mono_s = F("GeistMono-Regular.ttf", 34)
for i, r in enumerate(radii):
    y = cy + r
    milestone = (i + 1) % 6 == 0
    tk = 16 if milestone else 9
    a = 150 if milestone else 80
    mer.line([(cx - tk, y), (cx + tk, y)], fill=(*GOLD_PALE, a), width=3 if milestone else 2)
    if milestone:
        label = f"{i+1:02d}"
        bb = mer.textbbox((cx + 40, y), label, font=mono_s, anchor="lm")
        mer.rectangle([bb[0] - 12, bb[1] - 8, bb[2] + 12, bb[3] + 8], fill=(*INK, 255))
        mer.text((cx + 40, y), label, font=mono_s,
                 fill=(*GOLD_PALE, 210), anchor="lm")

base = Image.alpha_composite(base, rings)

# ---- the two lights, together on the 24th ring -----------------------
lights = Image.new("RGBA", (W, H), (0, 0, 0, 0))
ld = ImageDraw.Draw(lights)
R = radii[-1]
a_mid = math.radians(-58)
sep = math.radians(5.6)

# brightened passage of the shared orbit around the pair
arc_pts = []
for k in range(121):
    t = a_mid - math.radians(16) + math.radians(32) * k / 120
    arc_pts.append((cx + R * math.cos(t), cy + R * math.sin(t)))
ld.line(arc_pts, fill=(*GOLD_PALE, 210), width=5)

for da, core_r in [(-sep / 2, 13), (sep / 2, 11)]:
    px = cx + R * math.cos(a_mid + da)
    py = cy + R * math.sin(a_mid + da)
    for gr, ga in [(70, 10), (50, 24), (34, 48), (21, 95)]:
        ld.ellipse([px - gr, py - gr, px + gr, py + gr], fill=(*GOLD_PALE, ga))
    ld.ellipse([px - core_r, py - core_r, px + core_r, py + core_r],
               fill=(255, 246, 224, 255))
base = Image.alpha_composite(base, lights)

# ---- typography -------------------------------------------------------
td = ImageDraw.Draw(base)
mono = F("GeistMono-Regular.ttf", 44)
spaced_text(td, (cx, int(H * 0.062)), "TWENTY-FOUR YEARS", mono,
            (*GOLD_PALE, 220), tracking=30)

# small diamond under the header
dy = int(H * 0.088)
td.polygon([(cx, dy - 10), (cx + 10, dy), (cx, dy + 10), (cx - 10, dy)],
           outline=(*GOLD, 200), width=2)

# names: Italiana with an italic serif ampersand
name_f = F("Italiana-Regular.ttf", 168)
amp_f = F("InstrumentSerif-Italic.ttf", 132)
parts = [("Rob", name_f, CREAM), ("  &  ", amp_f, GOLD_PALE), ("Rae Lee", name_f, CREAM)]
widths = [td.textbbox((0, 0), t, font=f)[2] for t, f, _ in parts]
total = sum(widths)
x = cx - total / 2
ny = int(H * 0.855)
for (t, f, c), w in zip(parts, widths):
    td.text((x, ny), t, font=f, fill=c, anchor="lm")
    x += w

# hairline + years
ly = int(H * 0.912)
td.line([(cx - 170, ly), (cx - 30, ly)], fill=(*GOLD, 140), width=2)
td.polygon([(cx, ly - 7), (cx + 7, ly), (cx, ly + 7), (cx - 7, ly)],
           outline=(*GOLD, 170), width=2)
td.line([(cx + 30, ly), (cx + 170, ly)], fill=(*GOLD, 140), width=2)
spaced_text(td, (cx, int(H * 0.945)), "2002 · 2026", mono,
            (*GOLD, 190), tracking=26)

cover = base.convert("RGB")

# ----------------------------------------------------------------- PAGE 2
p2 = Image.new("RGB", (W, H), CREAM)
d2raw = p2.convert("RGBA")
d2 = ImageDraw.Draw(d2raw)

# faint paper grain
grain = Image.new("RGBA", (W, H), (0, 0, 0, 0))
gd = ImageDraw.Draw(grain)
for _ in range(4200):
    x, y = random.uniform(0, W), random.uniform(0, H)
    gd.point((x, y), fill=(180, 165, 135, random.randint(6, 16)))
d2raw = Image.alpha_composite(d2raw, grain)
d2 = ImageDraw.Draw(d2raw)

# motif: two interlocked rings, echo of the cover
mcx, mcy, mr = cx, int(H * 0.115), 78
for off in (-mr * 0.62, mr * 0.62):
    d2.ellipse([mcx + off - mr, mcy - mr, mcx + off + mr, mcy + mr],
               outline=(*GOLD, 235), width=4)
spaced_text(d2, (cx, mcy + mr + 70), "XXIV", F("GeistMono-Regular.ttf", 40),
            (*GOLD, 220), tracking=34)

# salutation
d2.text((cx, int(H * 0.255)), "Rob & Rae Lee,", font=F("Italiana-Regular.ttf", 118),
        fill=CREAM_INK, anchor="mm")

# message
msg_f = F("CrimsonPro-Italic.ttf", 74)
lines = [
    "Twenty-four years of marriage",
    "is a real accomplishment, and you've",
    "made every one of them count.",
    "",
    "You two make a great team. You work hard,",
    "you look out for each other, and you've built",
    "a life and a family to be proud of.",
    "",
    "We're very proud of you both,",
    "and we love you very much.",
]
y = int(H * 0.350)
for line in lines:
    if line:
        d2.text((cx, y), line, font=msg_f, fill=CREAM_INK, anchor="mm")
    y += 106 if line else 66

# closing
d2.text((cx, int(H * 0.648)), "Happy 24th Anniversary",
        font=F("Italiana-Regular.ttf", 128), fill=CREAM_INK, anchor="mm")

# signature
d2.text((cx, int(H * 0.732)), "With all our love,",
        font=F("CrimsonPro-Italic.ttf", 68), fill=CREAM_INK, anchor="mm")
d2.text((cx, int(H * 0.796)), "Mom & Dad",
        font=F("NothingYouCouldDo-Regular.ttf", 112), fill=(120, 90, 40), anchor="mm")
spaced_text(d2, (cx, int(H * 0.856)), "BOB & MARY FAY",
            F("GeistMono-Regular.ttf", 44), (*GOLD, 230), tracking=30)

# small closing diamond
dy2 = int(H * 0.912)
d2.polygon([(cx, dy2 - 9), (cx + 9, dy2), (cx, dy2 + 9), (cx - 9, dy2)],
           outline=(*GOLD, 200), width=2)

# hairline frame on interior
m = 110
d2.rectangle([m, m, W - m, H - m], outline=(*GOLD, 120), width=3)

interior = d2raw.convert("RGB")

# ----------------------------------------------------------------- OUTPUT
out = "/tmp/claude-0/-home-user-FAS/64751b3a-b857-5e14-8427-5e169e01c6c2/scratchpad"
cover.save(f"{out}/anniversary-card-cover.png")
cover.save(f"{out}/Rob-and-RaeLee-24th-Anniversary-Card.pdf", save_all=True,
           append_images=[interior], resolution=600.0)
interior.save(f"{out}/anniversary-card-inside.png")
print("done")
