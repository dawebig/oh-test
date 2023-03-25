<?php

namespace App\Services;

use App\Enums\UniversityNames;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class PointCalculationService
{
    private Collection $results;

    public function __construct()
    {
        $this->results = collect();
    }

    /* @param Request $request
    * @return JsonResponse
    */
    public function calculatePoint(Request $request)  {
        list($isValid, $validationMessage) = $this->validate($request);

        if(!$isValid) {
            return response()->json($validationMessage, Response::HTTP_BAD_REQUEST);
        }

        return response()->json(null, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function validate(Request $request) {
        $this->setResultToCollection($request);

        $foundRequiredSubjects = $this->validateAllContainSubject(['magyar nyelv és irodalom' ,'történelem', 'matematika']);
        if ( !$foundRequiredSubjects ) {
            return [false, 'A pontszámítás nem lehetséges. (Kötelező érettségi tantárgy hiánya)'];
        }

        $university = $this->getUniversityInformation($request);

        $foundUniversityRequired = $this->validateUniversityContainSubject($university);
        if ( !$foundUniversityRequired ) {
            return [false, 'A pontszámítás nem lehetséges. (Egyetemi kötelező tantárgy hiánya)'];
        }

        $foundUniversityOptionals = $this->validateContainOneOfSubject($university['subjects']['optionals']);
        if ( !$foundUniversityOptionals ) {
            return [false, 'A pontszámítás nem lehetséges. (Egyetemi kötelezően választható tantárgy hiánya)'];
        }

        if (!$this->validateResultUnderTwentyPercent()) {
            return [false, 'A pontszámítás nem lehetséges. (Sikertelen érettségi eredmény)'];
        }


        return [true, ''];
    }

    /**
     * @return bool
     */
    private function validateResultUnderTwentyPercent(): bool
    {
        $isUpper = true;
        foreach ($this->results as $item) {
            if ($item['result'] < 20 ) {
                $isUpper = false;
            }

        }
        return $isUpper;
    }

    /**
     * @param array $university
     * @return boolean
     */
    private function validateUniversityContainSubject(array $university): bool
    {
        $found = false;
        foreach ($this->results as $item) {
            if ( in_array($item['name'], [$university['subjects']['required']['name']]) ) {
                if (isset($university['subjects']['required']['level'])) {
                    if ($university['subjects']['required']['level'] == $item['type']) {
                        $found = true;
                    }
                }
                else {
                    $found = true;
                }
            }
        }
        return $found;
    }

    /**
     * @param array $subject
     * @return boolean
     */
    private function validateAllContainSubject(Array $subject): bool
    {
        $found = [];
        foreach ($this->results as $item) {

            if ( in_array($item['name'], $subject) ) {
                $found[] = $item['name'];
            }
        }
        return count($found) == count($subject);
    }

    /**
     * @param array $subject
     * @return boolean
     */
    private function validateContainOneOfSubject(Array $subject): bool
    {
        return $this->results->contains(function ($value) use ($subject) {
            return in_array($value['name'], $subject);
        });
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getUniversityInformation(Request $request)
    {
        $university = $request->get('valasztott-szak')['egyetem'];
        switch($university) {
            case UniversityNames::PPKE:
                return [
                    'name' => UniversityNames::PPKE,
                    'subjects' => [
                        'required' => ['name' => 'angol', 'level' => 'emelt'],
                        'optionals' => ['francia', 'német', 'olasz', 'orosz', 'történelem']
                    ]
                ];
            case UniversityNames::ELTE:
            default:
                return [
                    'name' => UniversityNames::ELTE,
                    'subjects' => [
                        'required' => ['name' => 'matematika'],
                        'optionals' => ['biológia', 'fizika', 'informatika', 'kémia']
                    ]
                ];
        }
    }

    /**
     * @param Request $request
     * @return void
     */
    private function setResultToCollection(Request $request)
    {
        $results = $request->get('erettsegi-eredmenyek');
        foreach ($results as $result) {
            $splitName = preg_split('/ nyelv$/', $result['nev']);
            $this->results->push([
                'name'   => $splitName[0],
                'result' => str_replace('%', '', $result['eredmeny']),
                'type'   => $result['tipus']
            ]);
        }
    }
}
