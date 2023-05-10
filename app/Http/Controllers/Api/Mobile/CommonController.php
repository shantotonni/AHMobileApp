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
use App\Models\ProblemSection;
use App\Models\Question;
use App\Models\ServiceType;
use App\Models\SpareParts;
use App\Models\User;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function getAllGeneratorInfoForWarehouse(){
        $generator_info = GeneratorInfo::with('brand')->where('delivery_status','pending')->latest()->get();
        return new GeneratorInfoCollection($generator_info);
    }

    public function getAllGeneratorInfo(){
        $generator_info = GeneratorInfo::with('brand')->where('delivery_status','delivered')->latest()->get();
        return new GeneratorInfoCollection($generator_info);
    }

    public function getAllTechnician(){
        $technician = User::where('role_id',3)->with('role')->paginate(20);
        return new TechnicianCollection($technician);
    }

    public function getAllServiceType(){
        $service_types = ServiceType::latest()->get();
        return new ServiceTypeCollection($service_types);
    }

    public function getAllProblemSection(){
        $problem_sections = ProblemSection::all();
        return new ProblemSectionCollection($problem_sections);
    }

    public function getAllQuestion(){
        $question = Question::all();
        return new QuestionCollection($question);
    }

    public function getAllParts(){
        $parts = SpareParts::all();
        return new SparePartsCollection($parts);
    }

    public function getAllGeneratorBasicInfo(){
        $generator_basic_info = GeneratorBasicInfo::all();
        return new GeneratorBasicInfoCollection($generator_basic_info);
    }
}
