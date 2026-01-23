from PIL import Image

def make_transparent(input_path, output_path):
    img = Image.open(input_path).convert('RGBA')
    datas = img.getdata()

    new_data = []
    # Threshold for "white"
    threshold = 240
    
    for item in datas:
        # If pixel is very light (r, g, b > threshold), make it transparent
        if item[0] > threshold and item[1] > threshold and item[2] > threshold:
            new_data.append((255, 255, 255, 0))
        else:
            new_data.append(item)

    img.putdata(new_data)
    img.save(output_path)

if __name__ == "__main__":
    make_transparent('/Users/slate22/.gemini/antigravity/brain/315c638f-b9e9-4e05-bced-8aab78a7d6e9/lauras_mercantile_logo_v4_white_bg_1768383323525.png', 'wp-content/themes/lauras-mercantile-hybrid-gpt/assets/images/logo-final.png')
