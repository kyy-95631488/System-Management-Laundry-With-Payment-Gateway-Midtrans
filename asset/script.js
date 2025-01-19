const paragraphs = document.querySelectorAll(".text-summary");
const wordLimit = 25;

paragraphs.forEach((paragraph) => {
  let words = paragraph.innerText.split(/\s+/);
  if (words.length > wordLimit) {
    paragraph.innerText = words.slice(0, wordLimit).join(" ") + "...";
  }
});
