<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideoRequest;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Throwable;

class VideoController extends Controller
{
    public function index()
    {
        return view('videos.index', [
            'items' => Video::orderBy('id', 'desc')->get(),
        ]);
    }

    public function show(Video $video)
    {
        return view('videos.show', [
            'item' => $video,
        ]);
    }

    public function store(StoreVideoRequest $request)
    {
        $video = Video::create([
            'id'            => app('snowflake')->id(),
            'disk'          => config('filesystems.default'),
            'original_name' => $request->video->getClientOriginalName(),
            'extension'     => $request->video->extension(),
            'path'          => $request->video->store('videos', config('filesystems.default')),
            'title'         => str_replace('.'.$request->video->extension(), '', $request->video->getClientOriginalName()),
            'size'         => $request->video->getSize(),
        ]);

        return redirect()->route('videos.index');
    }

    public function redo(Video $video)
    {
        // if(data_get($video, 'error', false)) {
            $video->error = null;
            $video->save();
            $video->update([
                'error' => null,
                'converted_for_thumbing_at' => null,
                'converted_for_downloading_at' => null,
                'converted_for_streaming_at' => null,
            ]);
            $video->letsConvert();
            // return redirect()->route('videos.show', $video->id);
        // }
        return redirect()->route('videos.index');
    }

    public function playlist($id, $filename)
    {
        return FFMpeg::dynamicHLSPlaylist()
            ->fromDisk(Video::DiskStream)
            ->open("$id/$filename")
            ->setKeyUrlResolver(function ($key) {
                return route('videos.secret', ['key' => $key]);
            })
            ->setMediaUrlResolver(function ($mediaFilename) use ($id) {
                return Storage::disk(Video::DiskStream)->url("$id/$mediaFilename");
            })
            ->setPlaylistUrlResolver(function ($playlistFilename) use ($id) {
                return route('videos.playlist', ['id' => $id, 'filename' => $playlistFilename]);
            });
    }
}
