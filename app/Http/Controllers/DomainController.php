<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Retrieve a list of domains and display them using a specific view.
        $domains = Domain::all();
        return view('domains.index', ['domains' => $domains]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Display a form for creating a new domain using a specific view.
        return view('domains.create');
    }

    function makeHash($domain)
    {
        $hashedUrl = hash('sha256', $domain);
        return "KE" . substr(md5($hashedUrl), 0, 29) . "Y";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'url' => 'required|max:99|unique:domains',
            'key' => 'max:32|unique:domains',
        ]);
        $validatedData['key'] = $this->makeHash($validatedData['url']);

        try {
            // Create a new domain based on the validated data
            $domain = Domain::create($validatedData);

            // Flash a success message to the session
            Session::flash('success', 'Data was successfully inserted.');
        } catch (\Exception $e) {
            // Handle any exceptions or errors that may occur during insertion
            // Flash an error message to the session
            Session::flash('error', 'An error occurred while inserting the data.');
        }
        // Redirect to the index page after creating the domain
        return redirect()->route('domains.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function show(Domain $domain)
    {
        // Display the details of a specific domain using a specific view.
        return view('domains.show', ['domain' => $domain]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function edit(Domain $domain)
    {
        // Display a form for editing a specific domain's information using a specific view.
        return view('domains.edit', ['domain' => $domain]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Domain $domain)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'url' => 'required|max:99|unique:domains,url,' . $domain->id,
            'key' => 'max:32|unique:domains,key,' . $domain->id,
        ]);
        // Update the domain's information based on the validated data
        try {
            // Create a new domain based on the validated data
            $domain->update($validatedData);
            // Flash a success message to the session
            Session::flash('success', 'Data was successfully Updated.');
        } catch (\Exception $e) {
            // Handle any exceptions or errors that may occur during insertion
            // Flash an error message to the session
            Session::flash('error', 'An error occurred while Updating the data.');
        }
        // Redirect to the index page after updating the domain
        return redirect()->route('domains.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Domain $domain)
    {
        // Delete the specific domain from storage
        $domain->delete();

        // Redirect to the index page after deleting the domain
        return redirect()->route('domains.index');
    }
}
