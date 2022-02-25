# 素材庫擴展包說明

## 安裝

首先確保 `Laravel` 已安裝，並且設定正確的資料庫連線。

在 `composer.json` 加入擴展包位置的資訊，假設擴展包位置在相對路徑的 `./repositories/target-convert/material`，那麼資訊應該如下

```
{
    // ...
    "repositories": [
        {
            "type": "path",
            "url": "./repositories/target-convert/material"
        }
    ],
    // ...(其餘略)
}
```

接著安裝擴展包

```
composer require target-convert/material
```

然後執行下列命令來發佈資源檔案

```
php artisan vendor:publish --provider="TargetConvert\Material\TargetConvertMaterialServiceProvider"
```

在該命令會生成組態文件 `config/material.php`，可以在裡面修改資料表名以及對應的模組(model)，建議都是用默認組態不修改。

然後運行下面的命令完成安裝：

```
php artisan material:install
```

### 相關文件

安裝完成之後,會在專案目錄中生成以下的文件:

#### 組態文件

素材庫所有組態(config)都在 `config/material.php` 文件中。

#### 創建目錄

安裝後素材庫會在 `storage/app` 和 `storage/app/public` 建立 `materials` 目錄，目的是存放用戶上傳的檔案。


