<x-layout>
    <section>
        <aside class="notice">
            <h4>Important</h4>
            <p>Cette formulaire est uniquement pour les jardins Guy-Gagnon et Woodland. Ce sont les jardins au bord de sur terre ferme, à Verdun, près de l'école de cirque et le CHSLD Manoir de Verdun.</p>
            <ul>
                <li>Pour les jardins à l'Île-des-Sœurs il faut communiquer avec la <a href="mailto:jardinsids@gmail.com">Société d'horticulture de l'Île-des-Sœurs</a></li>
                <li>Pour le jardin du Pacifique, (près des terrains de baseball sur boulevard Gaetan-Laberge), il faut communiquer avec le <a href="mailto:jardindupacifique@gmail.com">jardin du Pacifique</a>.</li>
                <li>Pour les jardins à l'hôpital Douglas il faut communiquer avec la <a href="https://www.shverdun.org">Société d'horticulture de Verdun</a>.</li>
            </ul>

            <div lang="en-CA">
                <p>This form is only for the Guy-Gagnon and Woodland gardens. These are the gardens along the waterfront on Verdun's "mainland," near the circus school and the CHSLD Manoir de Verdun.</p>
                <ul>
                    <li>For the gardens on Île-des-Soeurs please contact the <a href="mailto:jardinsids@gmail.com">Société d'horticulture de l'Île-des-Soeurs</a></li>
                    <li>For the jardin du Pacifique, (near the baseball diamonds on boulevard Gaetan-Laberge), please contact the <a href="mailto:jardindupacifique@gmail.com">jardin du Pacifique</a>.</li>
                    <li>For the gardens at the Douglas hospital please contact the <a href="https://www.shverdun.org">Société d'horticulture de Verdun</a>.</li>
                </ul>
            </div>
        </aside>
        <hr>
        <form action="{{ route('liste-attente.store') }}" method="POST" id="waitlist-form">
            @csrf
            <h2>Inscrivez-vous sur la liste d'attente<span lang="en-CA">Get on the Waitlist</span></h2>

            <p>Soumettez ce formulaire pour être inscrit sur la liste d'attente pour un jardin.</p>
            <p>Submit this form to be added to the waitlist for a garden.</p>

            @if (session('status') && session('status') === 'already on list')
                <div class="alert alert-warning" role="alert">
                    <p>Vous êtes déja inscrit sur la liste d'attente! Quelqu'un vous contactera quand une place est disponible.</p>
                    <p lang="en-CA">You are already on the wait list! Someone will contact you when a place is available.</p>
                </div>
            @endif

            @if (session('status') && session('status') === 'saved')
                <div class="alert alert-success" role="alert">
                    <p>Vous avez été ajouté à notre liste d'attente. Quelqu'un vous contactera quand une place est disponible.</p>
                    <p lang="en-CA">You have been added to the waitlist. Someone will contact you when a place is available.</p>
                </div>
            @endif

            <fieldset>
                <p>
                    <label for="name">Nom / <span lang="en-CA">Name</span></label>
                    <input type="text" name="name" id="name" value="{{old('name')}}" required>
                    @error('name')
                    <span class="error">{{$message}}</span>
                    @enderror
                </p>
                <p>
                    <label for="phone">Téléphone / <span lang="en-CA">Phone</span></label>
                    <input type="tel" name="phone" id="phone" minlength="10" value="{{old('phone')}}" required>
                    @error('phone')
                    <span class="error">{{$message}}</span>
                    @enderror
                </p>
                <p>
                    <label for="email">Courriel / <span lang="en-CA">Email</span></label>
                    <input type="email" name="email" id="email" value="{{old('email')}}">
                    @error('email')
                    <span class="error">{{$message}}</span>
                    @enderror
                </p>
                <p>
                    <label>Langue préféré / <span lang="en-CA">Preferred Language</span></label>
                    <label for="lang-fr"><input type="radio" value="fr" name="lang" {{ old('lang') == 'fr' ? ' checked' : '' }} id="lang-fr">Français</label>
                    <label for="lang-en"><input type="radio" value="en" name="lang" {{ old('lang') == 'en' ? ' checked' : '' }} id="lang-en" lang="en-CA">English</label>
                </p>

                <p>
                    <label for="note">Note / <span lang="en-CA">Note</span></label>
                    <textarea name="note" id="note">{{old('note')}}</textarea>
                    @error('note')
                    <span class="error">{{$message}}</span>
                    @enderror
                </p>

                {!! NoCaptcha::displaySubmit('waitlist-form', 'Soumettre / Submit') !!}

            </fieldset>
        </form>
    </section>
    {!! NoCaptcha::renderJs('fr-CA') !!}
</x-layout>

