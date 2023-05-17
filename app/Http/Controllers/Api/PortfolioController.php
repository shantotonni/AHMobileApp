<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portfolio\PortfolioStoreRequest;
use App\Http\Requests\Portfolio\PortfolioUpdateRequest;
use App\Http\Resources\Portfolio\PortfolioCollection;
use App\Models\Portfolio;
use Intervention\Image\Facades\Image;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::orderBy('ID','desc')->paginate(15);
        return new PortfolioCollection($portfolios);
    }

    public function store(PortfolioStoreRequest $request)
    {
        if ($request->has('PortfolioImage')) {
            $PortfolioImage = $request->PortfolioImage;
            $name = uniqid() . time() . '.' . explode('/', explode(':', substr($PortfolioImage, 0, strpos($PortfolioImage, ';')))[1])[1];
            Image::make($PortfolioImage)->save(public_path('images/portfolio/') . $name);
        } else {
            $name = 'not_found.jpg';
        }

        $portfolio = new Portfolio();
        $portfolio->PortfolioName = $request->PortfolioName;
        $portfolio->PortfolioImage = $name;
        $portfolio->Active = 'Y';
        $portfolio->save();
        return response()->json(['message' => 'Portfolio Created Successfully'], 200);
    }

    public function update(PortfolioUpdateRequest $request, $id)
    {
        $portfolio = Portfolio::where('ID', $id)->first();
        $PortfolioImage = $request->PortfolioImage;
        if ($PortfolioImage != $portfolio->PortfolioImage) {
            if ($request->has('PortfolioImage')) {
                //code for remove old file
                if ($portfolio->PortfolioImage != '' && $portfolio->PortfolioImage != null) {
                    $destinationPath = 'images/portfolio/';
                    $file_old = $destinationPath . $portfolio->PortfolioImage;
                    if (file_exists($file_old)) {
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($PortfolioImage, 0, strpos($PortfolioImage, ';')))[1])[1];
                Image::make($PortfolioImage)->resize(400, 300)->save(public_path('portfolio/') . $name);
            } else {
                $name = $portfolio->PortfolioImage;
            }
        } else {
            $name = $portfolio->PortfolioImage;
        }

        $portfolio->PortfolioName = $request->PortfolioName;
        $portfolio->PortfolioImage = $name;
        $portfolio->save();
        return response()->json(['message' => 'Portfolio Updated Successfully'], 200);
    }

    public function destroy($id)
    {
        Portfolio::where('ID', $id)->delete();
        return response()->json(['message' => 'Portfolio Deleted Successfully'], 200);
    }

    public function search($query)
    {
        return new PortfolioCollection(Portfolio::where('PortfolioName', 'LIKE', "%$query%")->latest()->paginate(20));
    }
}
