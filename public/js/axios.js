function OnClickLike(event) {
    event.preventDefault();

    const url = this.href;
    const spanCount = this.querySelector('span.js-likes');
    const spanColor = this.querySelector('span.js-color');
    const spancontent = this.querySelector('span.js-like-content');
    const icon = this.querySelector('i');

    axios.get(url).then(function (response) {

        spanCount.textContent = response.data.likes;

        if (icon.classList.contains('fas')) {
            icon.classList.replace('fas', 'far');
        } else {
            icon.classList.replace('far', 'fas');
        }

        if (response.data.message == 'DISLIKE') {
            spancontent.textContent = 'LIKE';
        } else {
            spancontent.textContent = 'DISLIKE';
        }

        if (spanColor.classList.contains('badge-primary')) {
            spanColor.classList.replace('badge-primary', 'badge-success');
        } else {
            spanColor.classList.replace('badge-success', 'badge-primary');
        }
    }).catch(function (err) {
        if (err.response.status === 403) {
            window.alert("connect first");
        }
    });
}
document.querySelectorAll('a.js-like').forEach(function (link) {
    link.addEventListener('click', OnClickLike);
});