function FileCreator (file, dataset, accept = null, bandwidth = 5)
{
    this._dataset = dataset;
    this._accept = accept || '*/*';
    this._file = file;
    this._part_size = (bandwidth || 5) * 1024 * 1024; // magabytes
    this._part_fails = [];
    this._data = {
        url: this._dataset.url,
        name: file.name,
        ext: (file.name.split('.') || []).pop(),
        size: file.size,
        stamp: Date.now(),
        succeed: 0,
        part_count: Math.ceil(file.size / this._part_size),
        parts: [] // 有順序的分割內容
    };
    if (this._data.name == this._data.ext)
        this._data.ext = null;

    this.init();
}

FileCreator.prototype.init = function () {
    // 建立可視化結構
    this.visualization();
    // 驗證
    if(this.isValidate()) {
        // 將內容分割
        for (var i = 0; i < this._data.part_count; ++i) {
            //計算起始與結束位置
            var start = i * this._part_size,
                end = Math.min(this._data.size, start + this._part_size);
            this._data.parts.push(this._file.slice(start, end));
        }
    }
}

// 取得可視化進度
FileCreator.prototype.visualization = function () {
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
FileCreator.prototype.isValidate = function ()
{
    // 檢查 post url
    if(!this._dataset.url) {
        this.throwException("File post url is not exists.");
        return false;
    }
    // 檢查 type
    if(typeof(this._accept) != 'string')
        this._accept = '*/*';
    let accept = this._accept.split('/');
    if(typeof(accept[0]) == 'undefined' || accept[0] == '*')
        accept[0] = '.*';
    if(typeof(accept[1]) == 'undefined' || accept[1] == '*')
        accept[1] = '.*';
    if(!this._file.type.match(accept.join('/'))) {
        this.throwException(this.get('name') + ': type is not accepted.');
        return false;
    }
    // 檢查 size
    if(this.get('size') > this._dataset.sizeLimit) {
        this.throwException(this.get('name') + ': size(' + (this.get('size') / 1024 / 1024).toFixed(2) + 'MB) big then ' + (this._dataset.sizeLimit / 1024 / 1024).toFixed(2) + 'MB');
        return false;
    }
    return true;
}

FileCreator.prototype.renew_process = function () {
    let process = Math.floor(this.get('succeed') / this.get('part_count') * 100),
        name = this.get('name');
    this._visualization_text.innerHTML = '<span title="' + name + '" class="text-base truncate max-w-sm flex-1 font-medium text-gray-700 dark:text-white">' + name + '</span>'
        + '<span class="text-sm flex-none font-medium text-gray-700 dark:text-white">' + process + '%</span>';
    this._visualization_progress.innerHTML = '<div class="bg-gray-600 h-2.5 rounded-full" style="width: ' + process + '%"></div>';
};

FileCreator.prototype.done = function () {
    let process = Math.floor(this.get('succeed') / this.get('part_count') * 100),
        name = this.get('name');
    this._visualization_text.innerHTML = '<span title="' + name + '" class="text-base truncate max-w-sm flex-1 font-medium text-gray-700 dark:text-white">' + '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-sky-600 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>' + name + '</span>'
        + '<span class="text-sm flex-none font-medium text-gray-700 dark:text-white">' + process + '%</span>';
};

FileCreator.prototype.set = function (key, value) {
    return _.set(this._data, key, value);
}

FileCreator.prototype.get = function (key) {
    return _.get(this._data, key);
}

FileCreator.prototype.all = function (key) {
    return _.pick(this._data, ['url', 'name', 'ext', 'size', 'stamp']);
}

FileCreator.prototype.upload = function (closure = null)
{
    let self = this;
    self.part_upload().then(function(response) {
        if(typeof(closure) == 'function')
            closure(self);
    })
    .catch(function(error) {
        let message = self.get('name') + ': ' + error.response.data.message;
        if(error.response.data.errors)
            for(var key in error.response.data.errors)
                error.response.data.errors[key].forEach(element => {
                    message += "\n-- " + element;
                });
        self.throwException(message, 'worring');
    });
}

FileCreator.prototype.throwException = function (message, type = 'error')
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

// 上傳檔案
FileCreator.prototype.part_upload = async function ()
{
    let self = this,
        count = this.get('parts').length;

    let uploads = [];
    for(var i = 0; i < count; i++)
    {
        var form = new FormData();
        form.append("data", this.get('parts.' + i));
        form.append("id", this.get('id')); // temporary id
        form.append("name", this.get('name'));
        form.append("stamp", this.get('stamp'));
        form.append("ext", this.get('ext'));
        form.append("size", this.get('size'));
        form.append("total", this.get('part_count'));
        form.append("index", i + 1);

        uploads.push(await axios({
            method: "post",
            url: this.get('url'),
            data: form,
            headers: { "Content-Type": "multipart/form-data" },
        })
        .then(function (response) {
            // done
            self._data.succeed += 1;
            self.renew_process();
            if(self._data.succeed == self._data.part_count) {
                self.done();
            }
        }));
    }
    return axios.all(uploads);
};

// 將 dom 初始化為上傳器
function dropPartUpload(item)
{
    let bandwidth = 2;
    if((item.accept || '').match('video/.*'))
        bandwidth = 10;
    item.addEventListener("dragover", function (ev) {
        ev.preventDefault();
    });
    // 使用點擊方式上傳檔案
    item.addEventListener("click", function (ev) {
        ev.preventDefault();
        let self = this, input = document.createElement('input');
        input.type = 'file';
        input.multiple = "multiple";
        if(this.hasAttributes()) {
            var attrs = this.attributes;
            for(var i = attrs.length - 1; i >= 0; i--) {
              input.setAttribute(attrs[i].name, attrs[i].value);
            }
        }
        input.addEventListener("change", function (event) {
            event.preventDefault();
            for(var i = 0; i < this.files.length; i++) {
                self.dispatchEvent(new CustomEvent('uploadToTemporary', {
                    detail: new FileCreator(this.files.item(i), this.dataset, this.accept, bandwidth)
                }));
            }
            this.remove();
        });
        input.click();
    });
    // 使用拖曳方式上傳檔案
    item.addEventListener("drop", function (ev) {
        ev.preventDefault();
        if (ev.dataTransfer.items) {
            // Use DataTransferItemList interface to access the file(s)
            for (var i = 0; i < ev.dataTransfer.items.length; i++) {
                // If dropped items aren't files, reject them
                var files = [];
                if (ev.dataTransfer.items[i].kind === 'file') {
                    this.dispatchEvent(new CustomEvent('uploadToTemporary', {
                        detail: new FileCreator(ev.dataTransfer.items[i].getAsFile(), this.dataset, this.accept, bandwidth)
                    }));
                }
            }
        } else {
            // Use DataTransfer interface to access the file(s)
            for (var i = 0; i < ev.dataTransfer.files.length; i++) {
                this.dispatchEvent(new CustomEvent('uploadToTemporary', {
                    detail: new FileCreator(ev.dataTransfer.files[i], this.dataset, this.accept, bandwidth)
                }));
            }
        }
    });
    item.addEventListener("uploadToTemporary", async function (ev) {
        let file = ev.detail;
        // 加入可視化元件
        this.appendChild(file.visualization());
        // 驗證通過取得暫存位置及編號
        if(file.isValidate()) {
            // 取得 temporary 空間並上傳檔案
            await axios.post(this.dataset.temporaryUrl, {
                type: this.dataset.type,
                extra_data: {
                    origin: file.all()
                }
            })
            .then(function (response) {
                // file.throwException('temporary_id has not created');
                // 取得 id 開始上傳檔案到暫存位置
                file.set('id', response.data.key);
                file.upload(function (obj) { // done for uploaded
                    this.dispatchEvent(new CustomEvent('fileUploaded', { detail: { file: obj } }));
                }.bind(this));
            }.bind(this))
            .catch(function (error) {
                file.throwException(error.response.data.message);
            });
        }
    });
}

document.addEventListener("DOMContentLoaded", function (event)
{
    for(var item of document.querySelectorAll('[data-rel="drop-uploader"]')) {
        dropPartUpload(item);
    }
});
