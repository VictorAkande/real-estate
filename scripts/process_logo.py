from PIL import Image, ImageDraw
import os

BASE = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SRC = os.path.join(BASE, "public", "images", "logo-source.jpeg")
OUT_DIR = os.path.join(BASE, "public", "images")

img = Image.open(SRC).convert("RGBA")

# Flood-fill the white background (outside the circular badge) to transparent,
# starting from each corner, with a threshold to swallow anti-aliased edge pixels.
for corner in [(0, 0), (img.width - 1, 0), (0, img.height - 1), (img.width - 1, img.height - 1)]:
    ImageDraw.floodfill(img, corner, (0, 0, 0, 0), thresh=40)

# Crop tight to the remaining (non-transparent) content.
bbox = img.getbbox()
img = img.crop(bbox)

master_path = os.path.join(OUT_DIR, "logo.png")
img.save(master_path, optimize=True)
print("master", img.size, "->", master_path)

sizes = {
    "logo-navbar.png": 160,   # displayed ~40-56px tall in navbar, 2-3x for retina
    "logo-admin.png": 128,
    "favicon-32.png": 32,
    "favicon-16.png": 16,
    "apple-touch-icon.png": 180,
}

for name, size in sizes.items():
    resized = img.resize((size, size), Image.LANCZOS)
    out_path = os.path.join(OUT_DIR, name)
    resized.save(out_path, optimize=True)
    print(name, resized.size, "->", out_path)

# Multi-size .ico for the browser tab favicon
ico_path = os.path.join(BASE, "public", "favicon.ico")
img.resize((256, 256), Image.LANCZOS).save(
    ico_path, sizes=[(16, 16), (32, 32), (48, 48), (64, 64), (128, 128), (256, 256)]
)
print("favicon.ico ->", ico_path)
