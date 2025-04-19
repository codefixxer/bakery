<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;

class ManageRecipeController extends Controller
{
    /**
     * Display the manage recipe form.
     *
     * @param  int  $id  Recipe ID.
     * @return \Illuminate\View\View
     */
    public function index($id)
{
 
    return view('frontend.managerecipes');
}

    /**
     * Update a recipe with calculated totals.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id  Recipe ID.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the request inputs.
        $request->validate([
            'display_quantity' => 'required|numeric',
            'sold_pieces'      => 'required|numeric',
            'sold_kg'          => 'required|numeric',
            'waste_pieces'     => 'required|numeric',
            'waste_kg'         => 'required|numeric',
        ]);

        // Retrieve the recipe from the database.
        $recipe = Recipe::findOrFail($id);

        // Convert the piece weight (in grams) from the database to kilograms.
        $pieceWeightGrams = $recipe->piece_weight;
        $pieceWeightKg    = $pieceWeightGrams / 1000;

        // Retrieve the input values.
        $soldPieces    = $request->input('sold_pieces');
        $soldKgManual  = $request->input('sold_kg');
        $wastePieces   = $request->input('waste_pieces');
        $wasteKgManual = $request->input('waste_kg');

        // Calculate total sold weight: sold from pieces (converted to kg) + manually entered sold kg.
        $computedSoldKg = ($soldPieces * $pieceWeightKg) + $soldKgManual;

        // Calculate total waste weight: waste from pieces (converted to kg) + manually entered waste kg.
        $computedWasteKg = ($wastePieces * $pieceWeightKg) + $wasteKgManual;

        // Calculate the total used weight (sold + waste).
        $totalUsedKg = $computedSoldKg + $computedWasteKg;

        // Retrieve the recipe weight (in kg) from the model.
        $recipeWeight = $recipe->recipe_weight;

        // Calculate the reuse weight by subtracting the total used weight from the recipe weight.
        $reuseTotalKg = $recipeWeight - $totalUsedKg;

        // Update the recipe model with the new values.
        $recipe->display_quantity = $request->input('display_quantity');
        $recipe->sold_pieces      = $soldPieces;
        $recipe->sold_kg          = $soldKgManual;
        $recipe->total_sold_kg    = round($computedSoldKg, 2);
        $recipe->waste_pieces     = $wastePieces;
        $recipe->waste_kg         = $wasteKgManual;
        $recipe->total_waste_kg   = round($computedWasteKg, 2);
        $recipe->reuse_total_kg   = round($reuseTotalKg, 2);

        // Save the updated recipe.
        $recipe->save();

        // Redirect back with a status message.
        return redirect()->back()->with('status', 'Recipe updated successfully!');
    }
}
