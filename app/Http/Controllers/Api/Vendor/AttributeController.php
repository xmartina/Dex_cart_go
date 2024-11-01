<?php

namespace App\Http\Controllers\Api\Vendor;

use Exception;
use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Models\AttributeValue;
use function GuzzleHttp\Psr7\str;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttributeResource;
use App\Http\Resources\AttributeLightResource;
use App\Http\Resources\AttributeValueResource;
use App\Repositories\Category\CategoryRepository;
use App\Http\Resources\AttributeValueLightResource;
use App\Repositories\Attribute\AttributeRepository;
use App\Http\Requests\Validations\CreateAttributeRequest;
use App\Http\Requests\Validations\UpdateAttributeRequest;

class AttributeController extends Controller
{
    private $attribute;

    /**
     * construct
     */
    public function __construct(AttributeRepository $attribute)
    {
        parent::__construct();
        $this->attribute = $attribute;
    }

    /**
     * @return \Illuminate\Http\Response
     * All Attribute  created by the vendor
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter');

        if ($filter == 'trash') {
            $attributes = $this->attribute->trashonly();
        } else {
            $attributes = Attribute::withCount(['attributeValues', 'categories'])
                ->mine()->paginate();
        }

        return AttributeLightResource::collection($attributes);
    }

    /**
     * attribute entity values of given attribute id
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function attribute_values(Request $request, $id)
    {
        $filter = $request->input('filter');

        $query = AttributeValue::where('attribute_id', $id);

        if ($filter == 'trash') {
            $query = $query->onlyTrashed();
        }

        $attValues = $query->paginate();

        return AttributeValueLightResource::collection($attValues);
    }

    /**
     * @return \Illuminate\Http\Response
     * Store data to Attribute Table
     */
    public function store(CreateAttributeRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $attribute = Attribute::create($request->all());
                // $attribute = Attribute::create($request->except(['categories_ids', '_token']));

                if ($request->has('categories_ids')) {
                    $attribute->categories()->sync($request->get('categories_ids'));
                }
            });
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        return response()->json(['message' => trans('api.attribute_created_successfully')], 200);
    }

    /**
     * Show
     *
     * @param Attribute $attribute
     * @return \Illuminate\Http\Response
     */
    public function show(Attribute $attribute)
    {
        return new AttributeResource($attribute);
    }

    /**
     * @param int $attrID
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAttributeRequest $request, $attrID)
    {
        // Need to do the permission check

        try {
            $attribute = $this->attribute->update($request, $attrID);

            if ($request->has('categories_ids')) {
                DB::transaction(function () use ($attribute, $request) {
                    $attribute->categories()->sync($request->get('categories_ids'));
                });
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        return  response()->json(['message' => trans('api.attribute_updated_successfully')], 200);
    }

    /**
     * @param int $attrID
     * @return \Illuminate\http\response
     */
    public function trash(Request $request, $attrID)
    {
        try {
            $this->attribute->trash($attrID);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        return response()->json(['message' => trans('api.attribute_trashed_successfully')], 200);
    }

    /**
     * @param int $attrID
     * @return \Illuminate\http\response
     */
    public function restore(Request $request, $attrID)
    {
        try {
            $this->attribute->restore($attrID);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
        return response()->json(['message' => trans('api.attribute_restore_successfully')], 200);
    }

    /**
     * @param int $attrID
     * @return \Illuminate\http\response
     */
    public function destroy(Request $request, $attrID)
    {
        try {
            $this->attribute->destroy($attrID);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        return response()->json(['message' => trans('api.attribute_deleted_successfully')], 200);
    }

    /**
     * Display the translation of a category in the specified language.
     *
     * @param Attribute $attribute The category to retrieve the translation for.
     * @param string $language The language code of the translation.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the translation details.
     */
    public function showTranslation(Attribute $attribute, string $language)
    {
        $attribute_translation = $attribute->translations()->where('lang', $language)->firstOrNew([
            'attribute_id' => $attribute->id,
            'lang' => $language,
            'translation' => []
        ]);

        $translation = $attribute_translation->translation;

        return response()->json([
            'name' => $translation['name'] ?? null,
            'lang' => $language
        ]);
    }

    public function storeTranslation(Attribute $attribute, string $selected_language)
    {
        $attribute_translation = $attribute->translations()->where('lang', $selected_language)->firstOrNew([
            'attribute_id' => $attribute->id,
            'lang' => $selected_language,
        ]);

        $attribute_translation->translation = [
            'name' => request('name'),
        ];

        $attribute_translation->save();

        return response()->json(['message' => trans('api.model_translation_saved_successfully', ['model' => 'Attribute'])]);
    }
}
