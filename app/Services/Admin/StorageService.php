<?php

namespace App\Services\Admin;

use App\Constants\Status;
use App\Models\Storage;
use Illuminate\Http\Request;

class StorageService
{
    public function getAllStorages()
    {
        return Storage::searchable(['name'])->orderBy('id', 'desc')->paginate(getPaginate());
    }

    public function getStorageForm($type, $id = null)
    {
        $storage = [];
        if ($id) {
            $storage = Storage::where('type', $type)->findOrFail($id);
        }
        return $storage;
    }

    public function saveWasabi(Request $request, $id = null): Storage
    {
        $request->validate(
            [
                'name'            => 'required',
                'wasabi.driver'   => 'required',
                'wasabi.key'      => 'required',
                'wasabi.secret'   => 'required',
                'wasabi.region'   => 'required',
                'wasabi.bucket'   => 'required',
                'wasabi.endpoint' => 'required',
                'available_space' => 'required|integer',
            ],
            [
                'wasabi.driver.required' => 'Wasabi driver field is required',
                'wasabi.key.required'    => 'Wasabi key field is required',
                'wasabi.secret.required' => 'Wasabi secret field is required',
                'wasabi.region.required' => 'Wasabi region field is required',
                'wasabi.bucket'          => 'Wasabi bucket field is required',
                'wasabi.endpoint'        => 'Wasabi endpoint field is required',
            ]
        );

        $wasabi = $id ? Storage::where('type', Status::WASABI_SERVER)->findOrFail($id) : new Storage();
        $wasabi->name            = $request->name;
        $wasabi->config          = $request->wasabi;
        $wasabi->type            = Status::WASABI_SERVER;
        $wasabi->available_space = $request->available_space;
        $wasabi->save();

        return $wasabi;
    }

    public function saveDigitalOcean(Request $request, $id = null): Storage
    {
        $request->validate(
            [
                'name'                   => 'required',
                'digital_ocean.driver'   => 'required',
                'digital_ocean.key'      => 'required',
                'digital_ocean.secret'   => 'required',
                'digital_ocean.region'   => 'required',
                'digital_ocean.bucket'   => 'required',
                'digital_ocean.endpoint' => 'required',
                'available_space'        => 'required|integer',
            ],
            [
                'digital_ocean.driver.required' => 'Digital ocean driver field is required',
                'digital_ocean.key.required'    => 'Digital ocean key field is required',
                'digital_ocean.secret.required' => 'Digital ocean secret field is required',
                'digital_ocean.region.required' => 'Digital ocean region field is required',
                'digital_ocean.bucket'          => 'Digital ocean bucket field is required',
                'digital_ocean.endpoint'        => 'Digital ocean endpoint field is required',
            ]
        );

        $digitalOcean = $id ? Storage::where('type', Status::DIGITAL_OCEAN_SERVER)->findOrFail($id) : new Storage();
        $digitalOcean->name            = $request->name;
        $digitalOcean->config          = $request->digital_ocean;
        $digitalOcean->type            = Status::DIGITAL_OCEAN_SERVER;
        $digitalOcean->available_space = $request->available_space;
        $digitalOcean->save();

        return $digitalOcean;
    }

    public function saveFtp(Request $request, $id = null): Storage
    {
        $request->validate(
            [
                'ftp.host_domain' => 'required|url',
                'ftp.host'        => 'required',
                'ftp.username'    => 'required',
                'ftp.password'    => 'required',
                'ftp.port'        => 'required|integer',
                'ftp.root_path'   => 'required',
                'available_space' => 'required|integer',
            ],
            [
                'ftp.host_domain.required' => 'Host domain is required',
                'ftp.host.required'        => 'Host is required',
                'ftp.username.required'    => 'Username is required',
                'ftp.password.required'    => 'Password is required',
                'ftp.port.required'        => 'Port is required',
                'ftp.root_path.required'   => 'Root path is required',
            ]
        );

        $ftp = $id ? Storage::where('type', Status::FTP_SERVER)->findOrFail($id) : new Storage();
        $ftp->name            = $request->name;
        $ftp->config          = $request->ftp;
        $ftp->type            = Status::FTP_SERVER;
        $ftp->available_space = $request->available_space;
        $ftp->save();

        return $ftp;
    }

    public function checkConfig($id): array
    {
        $storage = Storage::find($id);

        if (in_array($storage->type, [Status::WASABI_SERVER, Status::DIGITAL_OCEAN_SERVER])) {
            try {
                s3Client($storage)->headBucket([
                    'Bucket' => @$storage->config?->bucket,
                ]);
                return ['status' => 'success', 'message' => 'S3 is connected successfully!'];
            } catch (\Throwable $th) {
                return ['status' => 'error', 'message' => 'S3 connection failed. Please check your configuration.'];
            }
        } else {
            $response = ftp($storage);
            if ($response == false) {
                return ['status' => 'error', 'message' => 'Ftp connection failed. Please check your configuration.'];
            }
            ftp_close($response['ftpConn']);
            return ['status' => 'success', 'message' => 'Ftp is connected successfully!'];
        }
    }

    public function toggleStatus($id): mixed
    {
        return Storage::changeStatus($id);
    }
}
