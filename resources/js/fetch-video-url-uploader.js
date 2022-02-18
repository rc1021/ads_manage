function FetchVideoUrlCreator (url)
{
    this._request_url = 'https://youtube.com/oembed?url=' + url + '&format=json';
    this.getMetadata();
    // this._metadata = axios.get(this._request_url).data;
    // this._data = {};
    // if(this._metadata) {
    //     this._data = {
    //         url: this._metadata.provider_url,
    //         name: this._metadata.title,
    //         thumbnail_url: this._metadata.thumbnail_url
    //     };
    // }
    // this.init();
}

FetchVideoUrlCreator.prototype.getMetadata = async () => {
    const result = await axios.get(this._request_url);
    this._metadata = result.data;
    console.log(this._metadata)
  };

FetchVideoUrlCreator.prototype.init = async function () {
    console.log(this._metadata);
    // 建立可視化結構
    this.visualization();
    this.isValidate();
}

// 取得可視化進度
FetchVideoUrlCreator.prototype.visualization = function () {
    if(!this._visualization) {
        // text
        this._visualization_text = document.createElement("div");
        this._visualization_text.className = 'flex justify-between mb-1';
        // progress
        this._visualization_progress = document.createElement("div");
        this._visualization_progress.className = 'w-full bg-gray-100 rounded-full h-2.5 dark:bg-gray-700';
        // visualization
        this._visualization = document.createElement("div");
        this._visualization.className = 'z-50 p-4 py-3 text-slate-700 bg-white/90';
        this._visualization.appendChild(this._visualization_text);
        this._visualization.appendChild(this._visualization_progress);
    }
    return this._visualization;
}

// 驗證檔案
FetchVideoUrlCreator.prototype.isValidate = function ()
{
    // 檢查 video url metadata
    if(!this._metadata) {
        this.throwException("Video can not fetch.");
        return false;
    }
    return true;
}

FetchVideoUrlCreator.prototype.done = function () {
    let name = this.get('name');
    this._visualization_text.innerHTML = '<span title="' + name + '" class="text-base truncate max-w-sm flex-1 font-medium text-gray-700 dark:text-white">' + '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-main-600 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>' + name + '</span>'
        + '<span class="text-sm flex-none font-medium text-gray-700 dark:text-white">100%</span>'
        + '<input type="hidden" name="temporaries[]" value="' + this.get('id') + '">';
};

FetchVideoUrlCreator.prototype.set = function (key, value) {
    return _.set(this._data, key, value);
}

FetchVideoUrlCreator.prototype.get = function (key) {
    return _.get(this._data, key);
}

FetchVideoUrlCreator.prototype.all = function (key) {
    return _.pick(this._data, ['url', 'name', 'thumbnail_url']);
}

FetchVideoUrlCreator.prototype.upload = function (closure = null)
{
    self.done();
}

FetchVideoUrlCreator.prototype.throwException = function (message, type = 'error')
{
    let self = this, color = 'red';
    if(type == 'worring')
        color = 'amber';
    this._visualization.className = 'flex z-50 items-start p-4 py-3 text-' + color + '-600 bg-' + color + '-200 space-x-2 cursor-pointer transition-opacity ease-in-out';
    this._visualization.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-' + color + '-600 flex-none mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg><pre class="flex-1">' + message + '</pre>';
    this._visualization.alt = 'click to remove';
    this._visualization.addEventListener("fadeoutAndRemove", function (ev) {
        ev.target.className += ' opacity-0 duration-1000';
        setTimeout(function () {
            ev.target.remove();
        }, 1 * 1000)
    });
    if(type == 'error') {
        // 點擊移除
        this._visualization.addEventListener("click", function (ev) {
            ev.target.dispatchEvent(new CustomEvent('fadeoutAndRemove'));
        });
        // 3.5 秒自動移除
        setTimeout(function () {
            if(self._visualization)
                self._visualization.dispatchEvent(new CustomEvent('fadeoutAndRemove'));
        }, 3.5 * 1000)
    };
    console.error(message);
}

// 將 dom 初始化為上傳器
function fetchVideoUrlUploader(item)
{
    item.addEventListener("syncUrls", function (ev) {
        ev.preventDefault();
        let urls = this.value.split(/\r?\n/);
        for(let url of urls) {
            this.dispatchEvent(new CustomEvent('uploadToTemporary', {
                detail: new FetchVideoUrlCreator(url)
            }));
        }
        this.value = '';
    });
    item.addEventListener("uploadToTemporary", async function (ev) {
        let file = ev.detail;
        // 加入可視化元件
        document.getElementById(this.dataset.target).appendChild(file.visualization());
        // 驗證通過取得暫存位置及編號
        if(file.isValidate()) {
            // 取得 temporary 空間
            await axios.post(this.dataset.temporaryUrl, {
                type: this.dataset.type,
                metadata: file.all()
            })
            // 然後上傳檔案
            .then(function (response) {
                // file.throwException('temporary_id has not created');
                // 取得 id 開始上傳檔案到暫存位置
                file.set('id', response.data.key);
                file.upload();
            }.bind(this))
            .catch(function (error) {
                file.throwException(error.response.data.message);
            });
        }
    });
}

document.addEventListener("DOMContentLoaded", function (event)
{
    for(var item of document.querySelectorAll('[data-rel="fetch-video-url-uploader"]')) {
        fetchVideoUrlUploader(item);
    }
});
