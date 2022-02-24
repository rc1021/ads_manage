document.addEventListener("DOMContentLoaded", function (event)
{
    for(var item of document.querySelectorAll('[data-rel="vtt-background"]')) {

        item.addEventListener("mouseenter", function (event) {
            this.dataset.interval = setInterval(function () {
                this.ind = ++this.ind % ((this.cues.length > 5) ? 5 : this.cues.length);
                let text = this.cues[this.ind].text;
                let url = new URL(text);
                let xywh = url.hash.split('=').pop().split(',');
                this.element.style.backgroundPosition = 'left -' + xywh[0] + 'px top -' + xywh[1] + 'px';
                this.element.style.backgroundImage = 'url(' + url.origin + url.pathname + ')';
            }.bind({
                cues: JSON.parse(this.dataset.vttBackground).cues,
                ind: 0,
                element: this
            }), (item.dataset.during || 1) * 1000);
        });
        item.addEventListener("mouseleave", function (event) {
            if(this.dataset.interval) {
                clearInterval(this.dataset.interval);
                delete this.dataset.interval;
                this.style.backgroundImage = '';
            }
        });
    }
});
