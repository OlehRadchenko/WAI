const imageMaxBlock = document.getElementById("imageMaxiBlock") as HTMLElement;
const maxiImg = document.getElementById("maxiImg") as HTMLImageElement;
const captionText = document.getElementById("caption") as HTMLElement;
const closeBtn = document.querySelector(".close") as HTMLElement;

document.querySelectorAll(".mini").forEach(img => {
    const image = img as HTMLImageElement;
    image.addEventListener("click", () => {
    imageMaxBlock.style.display = "block";
    maxiImg.src = image.src.replace("_mini", "");
    captionText.textContent = image.alt;
    maxiImg.alt = image.alt;
    });
});

closeBtn.addEventListener("click", () => {
    imageMaxBlock.style.display = "none";
});

imageMaxBlock.addEventListener("click", (e : MouseEvent) => {
    if (e.target as HTMLElement === imageMaxBlock) imageMaxBlock.style.display = "none";
});