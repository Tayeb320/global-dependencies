<?php

namespace Itclanbd\GlobalServiceDependencies\Providers\Utils;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    // Only use original name
    public function uploadFile2($file, $upload_path = null, $delete_path = null, $use_original_name = false)
    {
        try {
            // Upload image
            // Delete old file
            if ($delete_path) {
                $this->delete($delete_path);
            }
            // Upload new file
            return $this->upload2($file, $upload_path, $use_original_name);
        } catch (Exception $ex) {
            return null;
        }
    }

    public function uploadFile($file, $upload_path = null, $delete_path = null, $use_original_name = false)
    {
        try {
            // Upload image
            // Delete old file
            if ($delete_path) {
                $this->delete($delete_path);
            }
            // Upload new file
            return $this->upload($file, $upload_path, $use_original_name);
        } catch (Exception $ex) {
            return null;
        }
    }

    // Upload file and save file name by given name
    public function uploadFileWithName($file, $set_file_name, $upload_path = null, $delete_path = null)
    {
        try {
            // Upload image
            // Delete old file
            if ($delete_path) {
                $this->delete($delete_path);
            }
            // Upload new file
            return $this->upload3($file, $upload_path, $set_file_name);
        } catch (Exception $ex) {
            return null;
        }
    }

    public function upload($file, $path = 'others', $use_original_name = false)
    {
        try {
            if (!$use_original_name) {
                $name = time() . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();
            } else {
                $full_name    = $file->getClientOriginalName();
                $extract_name = explode('.', $full_name);

                $name = $this->generateSlug($extract_name[0]) . '-' . time() . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();
            }
            // Store image to public disk
            $file->storeAs($path, $name);
            return $name ?? '';
        } catch (\Exception $ex) {
            return '';
        }
    }

    // Only use original name
    public function upload2($file, $path = 'others', $use_original_name = false)
    {
        try {
            if (!$use_original_name) {
                $name = time() . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();
            } else {
                $full_name    = $file->getClientOriginalName();
                $extract_name = explode('.', $full_name);

                $name = $this->generateSlug($extract_name[0]) . '.' . $file->getClientOriginalExtension();
            }
            // Store image to public disk
            $file->storeAs($path, $name);
            return $name ?? '';
        } catch (\Exception $ex) {
            return '';
        }
    }

    public function upload3($file, $path = 'others', $set_file_name = 'test')
    {
        try {
            $name = Str::slug($set_file_name) . '-' . time() . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();
            // Store image to public disk
            $file->storeAs($path, $name);
            return $name ?? '';
        } catch (\Exception $ex) {
            return '';
        }
    }

    public function uploadBase64($base64string, $path = 'others', $disk = 'public', $set_file_name = '')
    {
        try {
            $extension = explode('/', explode(':', substr($base64string, 0, strpos($base64string, ';')))[1])[1]; // .jpg .png .pdf
            $replace   = substr($base64string, 0, strpos($base64string, ',') + 1);
            $image     = str_replace($replace, '', $base64string);
            $image     = str_replace(' ', '+', $image);
            $imageName = Str::slug($set_file_name) . time() . rand(1111, 9999) . '.' . $extension;

            Storage::disk($disk)->put($path . '/' . $imageName, base64_decode($image));

            return $imageName ?? '';
        } catch (\Exception $ex) {
            log_error($ex);
            return '';
        }
    }

    public function delete($path = '')
    {
        try {
            // Delete image form public directory
            $path = storage_path("app/public/" . $path);
            if (file_exists($path)) {
                unlink($path);
            }

        } catch (\Exception $ex) {
        }
    }

    function generateSlug($value)
    {
        try {
            return preg_replace('/\s+/u', '-', trim(strtolower($value)));
        } catch (\Exception $e) {
            return '';
        }
    }
}
