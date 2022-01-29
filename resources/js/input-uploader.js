// 創建類型爲 UserException 的物件
function FileCreatorException(message, model = null) {
    this.message = message;
    this.name = "FileCreatorException";
    this.model = model;
  }

// 讓例外轉換成整齊的字串當它被當作字串使用時
FileCreatorException.prototype.toString = function() {
    return this.name + ': "' + this.message + '"';
}

function FileCreator (file, post_url, limit = null)
{
    if(!post_url)
        throw new FileCreatorException("File post url is not exists.");

    var part_size = (limit || 2) * 1024 * 1024; // magabytes
    this._file = file;
    this._part_fails = [];
    this._data = {
        url: post_url,
        name: file.name,
        ext: (file.name.split('.') || []).pop(),
        size: file.size,
        stamp: Date.now(),
        succeed: 0,
        part_count: Math.ceil(file.size / part_size),
        parts: [] // 有順序的分割內容
    };
    if (this._data.name == this._data.ext)
        this._data.ext = null;

    // 將內容分割
    for (var i = 0; i < this._data.part_count; ++i) {
        //計算起始與結束位置
        var start = i * part_size,
            end = Math.min(this._data.size, start + part_size);
        this._data.parts.push(file.slice(start, end));
    }
}

FileCreator.prototype.set = function (key, value) {
    return _.set(this._data, key, value);
}

FileCreator.prototype.get = function (key) {
    return _.get(this._data, key);
}

FileCreator.prototype.all = function (key) {
    return _.pick(this._data, ['url', 'name', 'ext', 'size', 'stamp']);
}
// 重新上傳檔案
FileCreator.prototype.restart_upload = function ()
{
    this._uploading_part = 0;
    this.Error = null;
    return this.next_upload();
}

// 上傳檔案
FileCreator.prototype.save = function ()
{
    let self = this,
        count = this.get('parts').length;

    let uploads = [];
    for(var i = 0; i < count; i++)
    {
        var form = new FormData();
        form.append("data", this.get('parts.' + i));
        form.append("id", this.get('id')); // material model id
        form.append("name", this.get('name'));
        form.append("stamp", this.get('stamp'));
        form.append("ext", this.get('ext'));
        form.append("size", this.get('size'));
        form.append("total", this.get('part_count'));
        form.append("index", i + 1);

        var process = axios({
            method: "post",
            url: this.get('url'),
            data: form,
            headers: { "Content-Type": "multipart/form-data" },
        })
        .then(function (response) {
            // done
            if(++self._data.succeed == self._data.part_count) {
                console.log('done for ' + self.get('name'));
            }
        });
        uploads.push(process);
    }
    return axios.all(uploads);
};

var files = window.files = [];

document.addEventListener("change", async function(event) {
    let input = event.target.closest('input');
    if (!input) return ;
    if (!document.contains(input)) return ;
    if(input.type != 'file' || input.dataset.rel != 'part-upload') return ;
    let form = input.closest('form');
    if (!form) return ;

    // 建立準備上傳的檔案群
    for (var i = 0; i < input.files.length; i++) {
        let blob = input.files.item(i),
            file = new FileCreator(blob, input.dataset.url, input.dataset.limit);
        // 為 blob 建立素材資料
        await axios.post(form.action, {
            title: file.get('name'),
            type: input.dataset.type,
            extra_data: {
                origin: file.all()
            }
        })
        .then(function (response) {
            // 只有素材建立成功時才有 model_id
            if(!response.data.id) {
                filwindow.fileses.push(new FileCreatorException('model has not created', file));
                return ;
            }
            file.set('id', response.data.id);
            file.set('name', response.data.title);
            file.save()
            .then(function(response) {
                files.push(file);
            })
            .catch(function(error) {
                window.files.push(new FileCreatorException(error.response.data.message, file));
            });
        })
        .catch(function (error) {
            window.files.push(new FileCreatorException(error.response.data.message, file));
        });
    }
    // reset input file
    input.value = '';
});

