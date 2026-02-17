from PIL import Image
import os

def remove_checkerboard(input_path, output_path):
    print(f"Processing {input_path}...")
    img = Image.open(input_path).convert('RGBA')
    pix = img.load()
    width, height = img.size

    # Target colors from subagent inspection
    # Square 1: [198, 200, 197, 255]
    # Square 2: [255, 255, 253, 255]
    
    def is_checker(r, g, b):
        # Check for Square 1 (Gray)
        if abs(r - 198) < 15 and abs(g - 200) < 15 and abs(b - 197) < 15:
            return True
        # Check for Square 2 (Near-white)
        if abs(r - 255) < 15 and abs(g - 255) < 15 and abs(b - 253) < 15:
            return True
        # Also handle absolute white and near-white just in case
        if r > 240 and g > 240 and b > 240:
            return True
        return False

    new_img = Image.new('RGBA', (width, height), (0, 0, 0, 0))
    new_pix = new_img.load()

    for y in range(height):
        for x in range(width):
            r, g, b, a = pix[x, y]
            if is_checker(r, g, b):
                new_pix[x, y] = (0, 0, 0, 0)
            else:
                new_pix[x, y] = (r, g, b, a)

    # Optional: Smooth edges or do a flood fill from corners if needed, 
    # but let's try the color match first.
    
    new_img.save(output_path)
    print(f"Saved to {output_path}")

if __name__ == "__main__":
    src = '/Users/slate22/antigravity/lauras-mercantile-theme/lauras-mercantile-theme/wp-content/themes/lauras-mercantile-theme-gpt-dev/assets/images/hero-bottles-improved.png'
    dest = '/Users/slate22/antigravity/lauras-mercantile-theme/lauras-mercantile-theme/wp-content/themes/lauras-mercantile-theme-gpt-dev/assets/images/hero-bottles-improved.png'
    remove_checkerboard(src, dest)
