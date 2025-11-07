const imageMaxBlock = document.getElementById("imageMaxiBlock");
const maxiImg = document.getElementById("maxiImg");
const captionText = document.getElementById("caption");
const closeBtn = document.querySelector(".close");
document.querySelectorAll(".mini").forEach(img => {
    const image = img;
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
imageMaxBlock.addEventListener("click", (e) => {
    if (e.target === imageMaxBlock)
        imageMaxBlock.style.display = "none";
});
export {};
//# sourceMappingURL=main.js.map