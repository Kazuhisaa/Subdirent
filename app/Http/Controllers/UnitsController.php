    <?php

    namespace App\Http\Controllers;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Models\Unit;

    class UnitsController extends Controller
    {
        // Add new Unit
        public function store(Request $request)
        {
            $fields = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'bedrooms' => 'nullable|integer|min:0',
                'bathrooms' => 'nullable|integer|min:0',
                'floor_area' => 'nullable|integer|min:0',
                'location' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            $imageName = null;
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/units'), $imageName);
            }

            $unit = Unit::create([
                'title' => strip_tags($request->title),
                'description' => strip_tags($request->description),
                'bedrooms' => $request->bedrooms,
                'bathrooms' => $request->bathrooms,
                'floor_area' => $request->floor_area,
                'location' => strip_tags($request->location),
                'price' => strip_tags($request->price),
                'image' => $imageName, // ✅ save filename
            ]);

            return response()->json([
                'message' => 'Unit created',
                'unit' => $unit
            ]);
        }

        // get all units
        public function index()
        {
            $unit = Unit::all();
            return response()->json($unit);
        }

        // show by id
        public function show($id)
        {
            $unit = Unit::findOrFail($id);
            return response()->json($unit);
        }

        // update
        public function update(Request $request, Unit $unit)
        {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'bedrooms' => 'nullable|integer|min:0',
                'bathrooms' => 'nullable|integer|min:0',
                'floor_area' => 'nullable|integer|min:0',
                'location' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/units'), $imageName);
                $validated['image'] = $imageName;
            }

            $unit->update($validated);

            return response()->json([
                'message' => 'Unit updated successfully',
                'unit' => $unit
            ]);
        }

        // delete
        public function destroy(Unit $unit)
        {
            $unit->delete();
            return response()->json(['message' => 'Unit deleted successfully']);
        }
    }
