        <!-- creation formulaire-->
        <!-- si je suis connecte à une session alors le mail de ma BDD correspond au mail de ma session-->
        <?php if (isset($_SESSION['email']) == true) {
            $result = $CV->gettout(["inputMail" => $_SESSION['email']]);
            // si dans ma BDD j'ai un ID à zero et donc non crée alors je creer un formulaire 
            if ($result["profil_id"] == 0) { ?>

                <article class="w-[100%] mx-auto p-4 shadow-4 rounded-3 bg-white/70 flex flex-col items-center">
                    <h2 class="text-3xl mb-5">Créer mon profil</h2>
                    <form method="post" class="flex flex-col items-center w-[50%]" enctype="multipart/form-data">
                        <div class="flex flex-col md:flex-row gap-3 w-[100%] mb-5">
                            <div class="flex md:w-[50%]">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    <i class="fa-solid fa-user"></i>
                                </span>
                                <input type="text" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Nom" name="Nom1" class="form-control" id="Nom" require>
                            </div>
                            <input type="text" class="rounded-md rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Prénom" type="text" name="Prenom1" class="form-control" id="Prenom" require>
                        </div>

                        <div class="flex flex-col md:flex-row w-[100%] mb-5">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <input type="email" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Email" type="email" name="mail1" class="form-control mb-3" id="Email1" require>
                        </div>

                        <div class="flex flex-col md:flex-row gap-3 w-[100%] mb-5">
                            <div class="flex md:w-[50%]">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    <i class="fa-solid fa-phone"></i>
                                </span>
                                <input class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tel Fixe" type="tel" name="fixe" class="form-control" id="fixe">
                            </div>
                            <input class="rounded-md rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tel Portable" type="tel" name="portable1" class="form-control" id="portable" require>
                        </div>


                        <div class="flex flex-col md:flex-row w-[100%] mb-5">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                <i class="fa-solid fa-cake-candles"></i>
                            </span>
                            <input type="date" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Date de Naissance" type="date" name="naissance1" class="form-control mb-3" id="naissance" require>
                        </div>

                        <div class="flex flex-col md:flex-row gap-3 w-[100%] mb-5">
                            <div class="flex md:w-[50%]">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    <i class="fa-solid fa-location-dot"></i>
                                </span>
                                <input class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Adresse" type="text" name="adresse" class="form-control" id="adresse">
                            </div>
                            <input class="rounded-md rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Complément d'adresse" type="text" name="adresse1" class="form-control" id="adresse1">
                        </div>

                        <div class="flex flex-col md:flex-row gap-3 w-[100%] mb-5">
                            <div class="flex md:w-[50%]">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    <i class="fa-solid fa-location-dot"></i>
                                </span>
                                <input class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Code Postal" type="text" name="postal" class="form-control" id="postal">
                            </div>
                            <input class="rounded-md rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Ville" type="text" name="ville" class="form-control" id="ville">
                        </div>

                        <div class="md:w-[100%]">
                            <input class="md:w-[100%] block text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="file_input_help" id="file_input" type="file" name="the_file">
                            <p class=" mt-1 text-sm text-gray-500 dark:text-gray-300 mb-5 " id="file_input_help">PDF, DOCX.</p>
                        </div>

                        <div class="flex flex-col md:flex-row w-[100%] mb-5">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                            <i class="fa-solid fa-hammer"></i>
                            </span>
                            <input type="text" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Domaine d'activité" name="domaine" class="form-control mb-3" id="domaine">
                        </div>

                        <div class="w-[100%] mb-10">
                            <div class="col-md-4">
                                <select placeholder="Compétences" class="block w-full rounded-sm cursor-pointer focus:outline-none" id="select-role" name="tags_new[]" multiple data-allow-new="true">
                                    <?php foreach ($CV->getCompetence() as $row) { ?>
                                        <option value="<?php print $row['Nom']; ?>"><?php print $row['Nom']; ?></option>
                                    <?php } ?>
                                </select>
                                <small class="text-muted opacity-25">Séléctionner vos compétences ou ajoutez les.</small>
                            </div>
                        </div>
                        <div class="">
                            <button type="submit" class="w-[250px] text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800" name="sauces">Créer</button>
                        </div>
                        </div>

                    </form>
                </article>
        <?php }
        }
        ?>