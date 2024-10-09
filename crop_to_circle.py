from PIL import Image, ImageDraw
import sys

def crop_to_circle(image_path, output_path):
    # Открываем изображение
    img = Image.open(image_path).convert("RGBA")
    
    # Создаем круглую маску
    size = img.size
    mask = Image.new('L', size, 0)
    draw = ImageDraw.Draw(mask)
    draw.ellipse((0, 0) + size, fill=255)
    
    # Применяем маску к изображению
    output = Image.new('RGBA', size)
    output.paste(img, (0, 0), mask)
    
    # Сохраняем результат
    output.save(output_path)

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Использование: python3 crop_to_circle.py <input_image> <output_image>")
        sys.exit(1)

    input_image = sys.argv[1]
    output_image = sys.argv[2]

    crop_to_circle(input_image, output_image)