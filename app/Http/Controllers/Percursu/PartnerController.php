<?php

namespace App\Http\Controllers\Percursu;

use App\Http\Controllers\Controller;
use UserResource;
use Partner;
use User;
use Address;
use Location;
use Experience;
use Formation;
use Skill;
use Site;
use Social;
use Courier;
use Phone;
use Illuminate\Http\Request;
use Auth;
use Folk;
use Common;
use PartnerCollection;
use PartnerResource;
use App\Http\Requests\Helpers\FolkRequest;
use App\Http\Requests\Percursu\PartnerRequest;


class PartnerController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('jwt.auth', ['except' => ['activedPartners', 'show', 'featuredPartners']]);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partners = Partner::orderBy('created_at', 'desc')->get();
        $partners->each(function ($partner) {
            $partner->load(
                'folk.user',
                'folk.phones',
                'folk.couriers',
                'folk.address.location',
                'skills',
                'formations',
                'experiences',
                'sites',
                'socials'
            );
        });
        return new PartnerCollection($partners);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PartnerRequest $request)
    {
        $folk = "";

        if ($request->not_mine && $request->new) {
            $folk = Folk::create([
                'name' => $request->input('folk.name'),
                'lastname' => $request->input('folk.lastname'),
                'gender' => $request->input('folk.gender'),
                'avatar' => $request->input('folk.avatar') ? Common::storeLocalFile($request->input('folk.avatar'), 'images/folks/avatar/') : 'default.svg',
                'cover' => $request->input('folk.cover') ? Common::storeLocalFile($request->input('folk.cover'), 'images/folks/cover/') : 'default.gif',
                'ic' => $request->input('folk.ic'),
                'nif' => $request->input('folk.nif'),
                'birthdate' => $request->input('folk.birthdate'),
            ]);
            $user = User::create([
                'email' => $request->input('user.email'),
                'username' => $request->input('user.username'),
                'password' => bcrypt($request->input('user.password')),
                'status' => true,
                'folk_id' => $folk->id,
            ]);
        }


        if ($request->not_mine && !$request->new) {
            $user = User::whereUsername($request->input('user.username'))->first();
            $folk = Folk::findOrfail($user->id);
        }

        if (!$request->not_mine) {
            $folk = Folk::findOrfail(auth()->user()->folk->id);
        }

        $folk->update([
            'name' => $request->input('folk.name'),
            'lastname' => $request->input('folk.lastname'),
            'gender' => $request->input('folk.gender'),
            'avatar' => $request->input('folk.avatar') ? Common::storeLocalFile($request->input('folk.avatar'), 'images/folks/avatar/') : $folk->avatar,
            'cover' => $request->input('folk.cover') ? Common::storeLocalFile($request->input('folk.cover'), 'images/folks/avatar/') : $folk->cover,
            'ic' => $request->input('folk.ic'),
            'nif' => $request->input('folk.nif'),
            'birthdate' => $request->input('folk.birthdate'),
        ]);

        if ($folk->partner) {
            return response()->json([
                'exist' => true,
                'msg' => 'Já tens um curriculum registado, se não consegues acessa-lo tente reiniciar a sua conta. Por agora alumas informações serão atualizadas',
            ]);
        }




        $location = Location::create([
            'lat' => $request->input('folk.address.location.lat'),
            'lng' => $request->input('folk.address.location.lng'),
        ]);

        $address = Address::create([
            'country' => $request->input('folk.address.country'),
            'city' => $request->input('folk.address.city'),
            'street' => $request->input('folk.address.street'),
            'postcode' => $request->input('folk.address.postcode'),
            'location_id' => $location->id,
            'folk_id' => $folk->id,
        ]);

        $partner = Partner::create([
            'status' => $request->status,
            'featured' => $request->featured,
            'promo' => $request->promo,
            'folk_id' => $folk->id
        ]);

        for ($i = 0; $i < count($request->input('folk.phones')); $i++) {
            $phone = new Phone();
            $phone->number = $request->input('folk.phones.' . $i . '.number');
            $phone->type = $request->input('folk.phones.' . $i . '.type');
            $phone->folk()->associate($folk);
            $phone->save();
        }

        for ($i = 0; $i < count($request->input('folk.couriers')); $i++) {
            $courier = new Courier();
            $courier->email = $request->input('folk.couriers.' . $i . '.email');
            $courier->type = $request->input('folk.couriers.' . $i . '.type');
            $courier->folk()->associate($folk);
            $courier->save();
        }

        if (sizeof($request->sites)) {
            for ($i = 0; $i < count($request->sites); $i++) {
                $site = new Site();
                $site->link = $request->input('sites.' . $i . '.link');
                $site->description = $request->input('sites.' . $i . '.description');
                $site->partner()->associate($partner);
                $site->save();
            }
        }

        if (sizeof($request->socials)) {
            for ($i = 0; $i < count($request->socials); $i++) {
                $site = new Social();
                $site->name = $request->input('socials.' . $i . '.name');
                $site->link = $request->input('socials.' . $i . '.link');
                $site->partner()->associate($partner);
                $site->save();
            }
        }

        for ($i = 0; $i < count($request->experiences); $i++) {
            $experience = new Experience();
            $experience->task = $request->input('experiences.' . $i . '.task');
            $experience->from = $request->input('experiences.' . $i . '.from');
            $experience->to = $request->input('experiences.' . $i . '.to');
            $experience->ongoing = $request->input('experiences.' . $i . '.ongoing');
            $experience->employer = $request->input('experiences.' . $i . '.employer');
            $experience->responsibility = $request->input('experiences.' . $i . '.responsibility');
            $experience->attachment = $request->input('experiences.' . $i . '.attachment');
            $experience->partner()->associate($partner);
            $experience->save();
        }

        for ($i = 0; $i < count($request->formations); $i++) {
            $formation = new Formation();
            $formation->designation = $request->input('formations.' . $i . '.designation');
            $formation->from = $request->input('formations.' . $i . '.from');
            $formation->to = $request->input('formations.' . $i . '.to');
            $formation->ongoing = $request->input('formations.' . $i . '.ongoing');
            $formation->institution = $request->input('formations.' . $i . '.institution');
            $formation->level = $request->input('formations.' . $i . '.level');
            $formation->subjects = $request->input('formations.' . $i . '.subjects');
            $formation->country = $request->input('formations.' . $i . '.country');
            $formation->city = $request->input('formations.' . $i . '.city');
            $formation->attachment = $request->input('formations.' . $i . '.attachment');
            $formation->partner()->associate($partner);
            $formation->save();
        }

        for ($i = 0; $i < count($request->skills); $i++) {
            $skill = new Skill();
            $skill->name = $request->input('skills.' . $i . '.name');
            $skill->description = $request->input('skills.' . $i . '.description');
            $skill->partner()->associate($partner);
            $skill->save();
        }

        if (count($request->charges) > 0) {
            $partner->charges()->sync($request->charges);
        }

        return response()->json([
            'exist' => false,
            'msg' => 'Dados armazenados com sucesso',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Percursu\Models\Percursu\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function show($username)
    {

        //Validadr estado de utilizador aqui...
        $partner = Partner::whereHas('folk', function ($q) use ($username) {
            $q->whereHas('user', function ($query) use ($username) {
                $query->whereUsername($username);
            });
        })->first();

        $partner->load(
            'folk.user',
            'folk.phones',
            'folk.couriers',
            'folk.address.location',
            'skills',
            'formations',
            'experiences',
            'sites',
            'socials'
        );
        return new PartnerResource($partner);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Percursu\Models\Percursu\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $partner = Partner::findOrFail($id);
        $folk = $partner->folk;
        $phones = $folk->phones;
        $couriers = $folk->couriers;
        $address = $folk->Address;
        $location = $address->location;
        $formations = $partner->formations;
        $experiences = $partner->experiences;
        $skills = $partner->skills;
        $sites = $partner->sites;
        $socials = $partner->socials;

        $folk->update([
            'name' => $request->input('folk.name'),
            'lastname' => $request->input('folk.lastname'),
            'gender' => $request->input('folk.gender'),
            'avatar' => $request->input('folk.avatar') == $folk->avatar ? $folk->avatar : Common::storeLocalFile($request->input('folk.avatar'), 'images/folks/avatar/'),
            'cover' => $request->input('folk.cover') == $folk->cover ? $folk->cover : Common::storeLocalFile($request->input('folk.cover'), 'images/folks/cover/'),
            'ic' => $request->input('folk.ic'),
            'nif' => $request->input('folk.nif'),
            'birthdate' => $request->input('folk.birthdate'),
        ]);

        $location->update([
            'lat' => $request->input('folk.address.location.lat'),
            'lng' => $request->input('folk.address.location.lng'),
        ]);

        $address->update([
            'country' => $request->input('folk.address.country'),
            'city' => $request->input('folk.address.city'),
            'street' => $request->input('folk.address.street'),
            'postcode' => $request->input('folk.address.postcode'),
            'location_id' => $location->id,
            'folk_id' => $folk->id,
        ]);

        //==================================================================0
        if (count($request->input('folk.phones')) > count($phones)) {
            for ($i = 0; $i < count($request->input('folk.phones')); $i++) {
                $phone = Phone::find($request->input('folk.phones.' . $i . '.id'));
                if ($phone === null) {
                    Phone::create([
                        'number' => $request->input('folk.phones.' . $i . '.number'),
                        'type' => $request->input('folk.phones.' . $i . '.type'),
                        'folk_id' => $folk->id,
                    ]);
                } else {
                    $phone->update([
                        'number' => $request->input('folk.phones.' . $i . '.number'),
                        'type' => $request->input('folk.phones.' . $i . '.type'),
                        'folk_id' => $folk->id,
                    ]);
                }
            }
        }

        if (count($request->input('folk.phones')) < count($phones)) {
            for ($i = 0; $i < count($phones); $i++) {
                $requestPhone = Phone::find($request->input('folk.phones.' . $i . '.id'));
                $phone = Phone::find($phones[$i]->id);
                if ($requestPhone === null) {
                    $phone->delete();
                } else {
                    $phone->update([
                        'number' => $request->input('folk.phones.' . $i . '.number'),
                        'type' => $request->input('folk.phones.' . $i . '.type'),
                        'folk_id' => $folk->id,
                    ]);
                }
            }
        }

        if (count($request->input('folk.phones')) == count($phones)) {
            for ($i = 0; $i < count($request->input('folk.phones')); $i++) {
                $phone = Phone::findOrFail($request->input('folk.phones.' . $i . '.id'));
                $phone->update([
                    'number' => $request->input('folk.phones.' . $i . '.number'),
                    'type' => $request->input('folk.phones.' . $i . '.type'),
                    'folk_id' => $folk->id,
                ]);
            }
        }

        //==================================================================0
        if (count($request->sites) > count($sites)) {
            for ($i = 0; $i < count($request->sites); $i++) {
                $site = Site::find($request->input('sites.' . $i . '.id'));
                if ($site === null) {
                    Site::create([
                        'link' => $request->input('sites.' . $i . '.link'),
                        'description' => $request->input('sites.' . $i . '.description'),
                        'partner_id' => $partner->id,
                    ]);
                } else {
                    $site->update([
                        'link' => $request->input('sites.' . $i . '.link'),
                        'description' => $request->input('sites.' . $i . '.description'),
                        'partner_id' => $partner->id,
                    ]);
                }
            }
        }

        if (count($request->sites) < count($sites)) {
            for ($i = 0; $i < count($sites); $i++) {
                $requestPhone = Site::find($request->input('sites.' . $i . '.id'));
                $site = Site::find($sites[$i]->id);
                if ($requestPhone === null) {
                    $site->delete();
                } else {
                    $site->update([
                        'link' => $request->input('sites.' . $i . '.link'),
                        'description' => $request->input('sites.' . $i . '.description'),
                        'partner_id' => $partner->id,
                    ]);
                }
            }
        }

        if (count($request->sites) == count($sites)) {
            for ($i = 0; $i < count($request->sites); $i++) {
                $site = Site::findOrFail($request->input('sites.' . $i . '.id'));
                $site->update([
                    'link' => $request->input('sites.' . $i . '.link'),
                    'description' => $request->input('sites.' . $i . '.description'),
                    'partner_id' => $partner->id,
                ]);
            }
        }

        //==================================================================0
        if (count($request->socials) > count($socials)) {
            for ($i = 0; $i < count($request->socials); $i++) {
                $social = Social::find($request->input('socials.' . $i . '.id'));
                if ($social === null) {
                    Social::create([
                        'name' => $request->input('socials.' . $i . '.name'),
                        'link' => $request->input('socials.' . $i . '.link'),
                        'partner_id' => $partner->id,
                    ]);
                } else {
                    $social->update([
                        'name' => $request->input('socials.' . $i . '.name'),
                        'link' => $request->input('socials.' . $i . '.link'),
                        'partner_id' => $partner->id,
                    ]);
                }
            }
        }

        if (count($request->socials) < count($socials)) {
            for ($i = 0; $i < count($socials); $i++) {
                $requestPhone = Social::find($request->input('socials.' . $i . '.id'));
                $social = Social::find($socials[$i]->id);
                if ($requestPhone === null) {
                    $social->delete();
                } else {
                    $social->update([
                        'name' => $request->input('socials.' . $i . '.name'),
                        'link' => $request->input('socials.' . $i . '.link'),
                        'partner_id' => $partner->id,
                    ]);
                }
            }
        }

        if (count($request->socials) == count($socials)) {
            for ($i = 0; $i < count($request->socials); $i++) {
                $social = Social::findOrFail($request->input('socials.' . $i . '.id'));
                $social->update([
                    'name' => $request->input('socials.' . $i . '.name'),
                    'link' => $request->input('socials.' . $i . '.link'),
                    'partner_id' => $partner->id,
                ]);
            }
        }

        //==================================================================
        if (count($request->input('folk.couriers')) > count($couriers)) {
            for ($i = 0; $i < count($request->input('folk.couriers')); $i++) {
                $courier = Courier::find($request->input('folk.couriers.' . $i . '.id'));
                if ($courier === null) {
                    Courier::create([
                        'email' => $request->input('folk.couriers.' . $i . '.email'),
                        'type' => $request->input('folk.couriers.' . $i . '.type'),
                        'folk_id' => $folk->id,
                    ]);
                } else {
                    $courier->update([
                        'email' => $request->input('folk.couriers.' . $i . '.email'),
                        'type' => $request->input('folk.couriers.' . $i . '.type'),
                        'folk_id' => $folk->id,
                    ]);
                }
            }
        }

        if (count($request->input('folk.couriers')) < count($couriers)) {
            for ($i = 0; $i < count($couriers); $i++) {
                $requestPhone = Courier::find($request->input('folk.couriers.' . $i . '.id'));
                $courier = Courier::find($couriers[$i]->id);
                if ($requestPhone === null) {
                    $courier->delete();
                } else {
                    $courier->update([
                        'email' => $request->input('folk.couriers.' . $i . '.email'),
                        'type' => $request->input('folk.couriers.' . $i . '.type'),
                        'folk_id' => $folk->id,
                    ]);
                }
            }
        }

        if (count($request->input('folk.couriers')) == count($couriers)) {
            for ($i = 0; $i < count($request->input('folk.couriers')); $i++) {
                $courier = Courier::findOrFail($request->input('folk.couriers.' . $i . '.id'));
                $courier->update([
                    'email' => $request->input('folk.couriers.' . $i . '.email'),
                    'type' => $request->input('folk.couriers.' . $i . '.type'),
                    'folk_id' => $folk->id,
                ]);
            }
        }

        //==================================================================
        if (count($request->formations) > count($formations)) {
            for ($i = 0; $i < count($request->formations); $i++) {
                $formation = Formation::find($request->input('formations.' . $i . '.id'));
                if ($formation === null) {
                    Formation::create([
                        'designation' => $request->input('formations.' . $i . '.designation'),
                        'from' => $request->input('formations.' . $i . '.from'),
                        'to' => $request->input('formations.' . $i . '.to'),
                        'ongoing' => $request->input('formations.' . $i . '.ongoing'),
                        'institution' => $request->input('formations.' . $i . '.institution'),
                        'subjects' => $request->input('formations.' . $i . '.subjects'),
                        'level' => $request->input('formations.' . $i . '.level'),
                        'country' => $request->input('formations.' . $i . '.country'),
                        'city' => $request->input('formations.' . $i . '.city'),
                        'attachment' => $request->input('formations.' . $i . '.attachment'),
                        'partner_id' => $partner->id,
                    ]);
                } else {
                    $formation->update([
                        'designation' => $request->input('formations.' . $i . '.designation'),
                        'from' => $request->input('formations.' . $i . '.from'),
                        'to' => $request->input('formations.' . $i . '.to'),
                        'ongoing' => $request->input('formations.' . $i . '.ongoing'),
                        'institution' => $request->input('formations.' . $i . '.institution'),
                        'subjects' => $request->input('formations.' . $i . '.subjects'),
                        'level' => $request->input('formations.' . $i . '.level'),
                        'country' => $request->input('formations.' . $i . '.country'),
                        'city' => $request->input('formations.' . $i . '.city'),
                        'attachment' => $request->input('formations.' . $i . '.attachment'),
                        'partner_id' => $partner->id,
                    ]);
                }
            }
        }

        if (count($request->formations) < count($formations)) {
            for ($i = 0; $i < count($formations); $i++) {
                $requestPhone = Formation::find($request->input('formations.' . $i . '.id'));
                $formation = Formation::find($formations[$i]->id);
                if ($requestPhone === null) {
                    $formation->delete();
                } else {
                    $formation->update([
                        'designation' => $request->input('formations.' . $i . '.designation'),
                        'from' => $request->input('formations.' . $i . '.from'),
                        'to' => $request->input('formations.' . $i . '.to'),
                        'ongoing' => $request->input('formations.' . $i . '.ongoing'),
                        'institution' => $request->input('formations.' . $i . '.institution'),
                        'subjects' => $request->input('formations.' . $i . '.subjects'),
                        'level' => $request->input('formations.' . $i . '.level'),
                        'country' => $request->input('formations.' . $i . '.country'),
                        'city' => $request->input('formations.' . $i . '.city'),
                        'attachment' => $request->input('formations.' . $i . '.attachment'),
                        'partner_id' => $partner->id,
                    ]);
                }
            }
        }

        if (count($request->formations) == count($formations)) {
            for ($i = 0; $i < count($request->formations); $i++) {
                $formation = Formation::findOrFail($request->input('formations.' . $i . '.id'));
                $formation->update([
                    'designation' => $request->input('formations.' . $i . '.designation'),
                    'from' => $request->input('formations.' . $i . '.from'),
                    'to' => $request->input('formations.' . $i . '.to'),
                    'ongoing' => $request->input('formations.' . $i . '.ongoing'),
                    'institution' => $request->input('formations.' . $i . '.institution'),
                    'subjects' => $request->input('formations.' . $i . '.subjects'),
                    'level' => $request->input('formations.' . $i . '.level'),
                    'country' => $request->input('formations.' . $i . '.country'),
                    'city' => $request->input('formations.' . $i . '.city'),
                    'attachment' => $request->input('formations.' . $i . '.attachment'),
                    'partner_id' => $partner->id,
                ]);
            }
        }

        //==================================================================

        if (count($request->experiences) > count($experiences)) {
            for ($i = 0; $i < count($request->experiences); $i++) {
                $experience = Experience::find($request->input('experiences.' . $i . '.id'));
                if ($experience === null) {
                    Experience::create([
                        'task' => $request->input('experiences.' . $i . '.task'),
                        'from' => $request->input('experiences.' . $i . '.from'),
                        'to' => $request->input('experiences.' . $i . '.to'),
                        'ongoing' => $request->input('experiences.' . $i . '.ongoing'),
                        'employer' => $request->input('experiences.' . $i . '.employer'),
                        'responsibility' => $request->input('experiences.' . $i . '.responsibility'),
                        'attachment' => $request->input('experiences.' . $i . '.attachment'),
                        'partner_id' => $partner->id,
                    ]);
                } else {
                    $experience->update([
                        'task' => $request->input('experiences.' . $i . '.task'),
                        'from' => $request->input('experiences.' . $i . '.from'),
                        'to' => $request->input('experiences.' . $i . '.to'),
                        'ongoing' => $request->input('experiences.' . $i . '.ongoing'),
                        'employer' => $request->input('experiences.' . $i . '.employer'),
                        'responsibility' => $request->input('experiences.' . $i . '.responsibility'),
                        'attachment' => $request->input('experiences.' . $i . '.attachment'),
                        'partner_id' => $partner->id,
                    ]);
                }
            }
        }

        if (count($request->experiences) < count($experiences)) {
            for ($i = 0; $i < count($experiences); $i++) {
                $requestPhone = Experience::find($request->input('experiences.' . $i . '.id'));
                $experience = Experience::find($experiences[$i]->id);
                if ($requestPhone === null) {
                    $experience->delete();
                } else {
                    $experience->update([
                        'task' => $request->input('experiences.' . $i . '.task'),
                        'from' => $request->input('experiences.' . $i . '.from'),
                        'to' => $request->input('experiences.' . $i . '.to'),
                        'ongoing' => $request->input('experiences.' . $i . '.ongoing'),
                        'employer' => $request->input('experiences.' . $i . '.employer'),
                        'responsibility' => $request->input('experiences.' . $i . '.responsibility'),
                        'attachment' => $request->input('experiences.' . $i . '.attachment'),
                        'partner_id' => $partner->id,
                    ]);
                }
            }
        }

        if (count($request->experiences) == count($experiences)) {
            for ($i = 0; $i < count($request->experiences); $i++) {
                $experience = Experience::findOrFail($request->input('experiences.' . $i . '.id'));
                $experience->update([
                    'task' => $request->input('experiences.' . $i . '.task'),
                    'from' => $request->input('experiences.' . $i . '.from'),
                    'to' => $request->input('experiences.' . $i . '.to'),
                    'ongoing' => $request->input('experiences.' . $i . '.ongoing'),
                    'employer' => $request->input('experiences.' . $i . '.employer'),
                    'responsibility' => $request->input('experiences.' . $i . '.responsibility'),
                    'attachment' => $request->input('experiences.' . $i . '.attachment'),
                    'partner_id' => $partner->id,
                ]);
            }
        }
        //========================================================================
        if (count($request->skills) > count($skills)) {
            for ($i = 0; $i < count($request->skills); $i++) {
                $skill = Skill::find($request->input('skills.' . $i . '.id'));
                if ($skill === null) {
                    Skill::create([
                        'name' => $request->input('skills.' . $i . '.name'),
                        'description' => $request->input('skills.' . $i . '.description'),
                        'partner_id' => $partner->id,
                    ]);
                } else {
                    $skill->update([
                        'name' => $request->input('skills.' . $i . '.name'),
                        'description' => $request->input('skills.' . $i . '.description'),
                        'partner_id' => $partner->id,
                    ]);
                }
            }
        }

        if (count($request->skills) < count($skills)) {
            for ($i = 0; $i < count($skills); $i++) {
                $requestPhone = Skill::find($request->input('skills.' . $i . '.id'));
                $skill = Skill::find($skills[$i]->id);
                if ($requestPhone === null) {
                    $skill->delete();
                } else {
                    $skill->update([
                        'name' => $request->input('skills.' . $i . '.name'),
                        'description' => $request->input('skills.' . $i . '.description'),
                        'partner_id' => $partner->id,
                    ]);
                }
            }
        }

        if (count($request->skills) == count($skills)) {
            for ($i = 0; $i < count($request->skills); $i++) {
                $skill = Skill::findOrFail($request->input('skills.' . $i . '.id'));
                $skill->update([
                    'name' => $request->input('skills.' . $i . '.name'),
                    'description' => $request->input('skills.' . $i . '.description'),
                    'partner_id' => $partner->id,
                ]);
            }
        }
        //==================================================================0

        return response()->json([
            'msg' => 'As suas informações na base de dados foram atualizadas',
            'formation' => $formation

        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Percursu\Models\Percursu\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $partner = Partner::find($id);
        $folk = $partner->folk;
        $phones = $folk->phones;
        $couriers = $folk->couriers;
        $address = $folk->Address;
        $location = $address->location;
        $formations = $partner->formations;
        $experiences = $partner->experiences;
        $skills = $partner->skills;
        $sites = $partner->sites;
        $socials = $partner->socials;

        if ($partner->status) {
            return response()->json([
                'msg' => 'Um curriculum publicado não pode ser iliminado, desative-o e tente novamente',
                'status' => true
            ]);
        }


        if ($phones) {
            foreach ($phones as $phone) {
                $phone->delete();
            }
        }

        if ($couriers) {
            foreach ($couriers as $courier) {
                $courier->delete();
            }
        }

        if ($formations) {
            foreach ($formations as $formation) {
                $formation->delete();
            }
        }

        if ($experiences) {
            foreach ($experiences as $experience) {
                $experience->delete();
            }
        }
        if ($skills) {
            foreach ($skills as $skill) {
                $skill->delete();
            }
        }
        foreach ($sites as $site) {
            $site->delete();
        }

        if ($socials) {
            foreach ($socials as $social) {
                $social->delete();
            }
        }

        $location->delete();
        $address->delete();
        $partner->delete();

        return response()->json([
            'msg' => 'Dados eliminados com sucesso',
            'status' => false
        ]);
    }

    // My methods
    public function changePartnerActivation($id)
    {
        $partner = Partner::findOrfail($id);
        $partner->status = !$partner->status;
        $partner->update();

        return response()->json([
            'msg' => 'Operação efetuado do com sucesso ',
        ]);
    }

    public function changePartnerFeatured($id)
    {
        $partner = Partner::findOrfail($id);
        $partner->featured = !$partner->featured;
        $partner->update();

        return response()->json([
            'msg' => 'Operação efetuado do com sucesso ',
        ]);
    }

    public function changePartnerPromotion($id)
    {
        $partner = Partner::findOrfail($id);
        $partner->promo = !$partner->promo;
        $partner->update();

        return response()->json([
            'msg' => 'Operação efetuado do com sucesso ',
        ]);
    }

    public function activedPartners()
    {
        $partners = Partner::whereStatus(true)->orderBy('created_at', 'desc')->get();
        $partners->each(function ($partner) {
            $partner->load(
                'folk.user',
                'folk.phones',
                'folk.couriers',
                'folk.address.location',
                'skills',
                'formations',
                'experiences',
                'sites',
                'socials'
            );
        });
        return new PartnerCollection($partners);
    }

    public function featuredPartners()
    {
        $partners = Partner::whereStatus(true)->whereFeatured(true)->orderBy('created_at', 'desc')->get();
        $partners->each(function ($partner) {
            $partner->load(
                'folk.user',
                'folk.phones',
                'folk.couriers',
                'folk.address.location',
                'skills',
                'formations',
                'experiences',
                'sites',
                'socials'
            );
        });
        return new PartnerCollection($partners);
    }

   
}
