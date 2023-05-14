<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Generator\GeneratorBasicInfoCollection;
use App\Http\Resources\GeneratorInfo\GeneratorInfoCollection;
use App\Http\Resources\ProblemSection\ProblemSectionCollection;
use App\Http\Resources\Question\QuestionCollection;
use App\Http\Resources\ServiceType\ServiceTypeCollection;
use App\Http\Resources\SparePartsCollection;
use App\Http\Resources\Technician\TechnicianCollection;
use App\Models\GeneratorBasicInfo;
use App\Models\GeneratorInfo;
use App\Models\Portfolio;
use App\Models\ProblemSection;
use App\Models\Question;
use App\Models\ServiceType;
use App\Models\SpareParts;
use App\Models\User;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function getAllPortfolio(){
        $portfolios = Portfolio::orderBy('CreatedDate','desc')->get();
        return response()->json([
            'portfolios'=>$portfolios
        ]);
    }

}
