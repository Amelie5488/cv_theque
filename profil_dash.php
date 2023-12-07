 <!-- si je suis connecte à une session -->
        <?php if (isset($_SESSION['email']) == true) {
            $result = $CV->gettout(["inputMail" => $_SESSION['email']]);
            // si j'ai un ID superieur à zero et donc existant alors j'affiche mon profil 
            if ($result["profil_id"] > 0) {
        ?>
                <!-- Afficher le profil et le modifier -->
                <section class="flex flex-col items-center p-5 md:m-5 bg-white/60">
                    <?php
                    $touslescandidats = $CV->getmonprofil(["inputMail" => $_SESSION["email"]]); ?>
                    <article class="flex flex-col mb-5 min-w-[60%]">
                        
                        <form class="flex flex-col justify-center items-center" method="post" enctype="multipart/form-data">

                            <div class="flex flex-col md:flex-row gap-3 w-[90%] mb-5">
                                <div class="flex md:w-[50%]">
                                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                        <i class="fa-solid fa-user"></i>
                                    </span>
                                    <input type="text" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="n1" id="" value="<?= $touslescandidats["Nom"] ?>">
                                </div>
                                <input type="text" class="w-full rounded-md rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="p1" id="" value="<?= $touslescandidats["Prenom"] ?>">
                            </div>


                            <div class="flex flex-col md:flex-row gap-3 w-[90%] mb-5">
                                <div class="flex md:w-[50%]">
                                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                        <i class="fa-solid fa-cake-candles"></i>
                                    </span>
                                    <input type="text" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="a1" id="" value="<?= $touslescandidats["Age"] ?>">
                                </div>
                                <input type="date" class="rounded-md rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="d1" id="" value="<?= $touslescandidats["Date_naissance"] ?>" require>
                            </div>

                            <div class="flex flex-col md:flex-row gap-3 w-[90%] mb-5">
                                <div class="flex md:w-[50%]">
                                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </span>
                                    <input type="text" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="ad1" id="" value="<?= $touslescandidats["Adresse"] ?>">
                                </div>
                                <input type="text" class="rounded-md rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="add1" id="" value="<?= $touslescandidats["Adresse_1"] ?>">
                            </div>


                            <div class="flex flex-col md:flex-row gap-3 w-[90%] mb-5">
                                <div class="flex md:w-[50%]">
                                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </span>
                                    <input type="text" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="po1" id="" value="<?= $touslescandidats["Code_postal"] ?>">
                                </div>
                                <input type="text" class="rounded-md rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="v1" id="" value="<?= $touslescandidats["ville"] ?>">
                            </div>

                            <div class="flex flex-col md:flex-row gap-3 w-[90%] mb-5">
                                <div class="flex md:w-[50%]">
                                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                        <i class="fa-solid fa-phone"></i>
                                    </span>
                                    <input type="number" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="t1" id="" value="<?= $touslescandidats["tel_portable"] ?>">
                                </div>
                                <input type="number" class="rounded-md rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="f1" id="" value="<?= $touslescandidats["tel_fixe"] ?>">
                            </div>

                            <div class="flex w-[90%] mb-5">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    <i class="fa-solid fa-envelope"></i>
                                </span>
                                <input type="email" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="e1" id="" value="<?= $touslescandidats["Email"] ?>">
                            </div>

                            <div class="flex w-[90%] mb-5">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                <i class="fa-solid fa-hammer"></i>
                                </span>
                                <input type="text" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="pr1" id="" value="<?= $touslescandidats["Profil"] ?>">
                            </div>

                            <div class="flex w-[90%] mb-5">
                                <input type="text" name="Mytags" multiple class="min-w-full max-w-[100vh] tagify leading-5 relative text-sm py-2 px-4 rounded text-gray-800 bg-white border border-gray-300 overflow-x-auto focus:outline-none focus:border-gray-400 focus:ring-0 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-700 dark:focus:border-gray-600" value='<?= $touslescandidats["Competence_1"] ?>, <?= $touslescandidats["Competence_2"] ?>, <?= $touslescandidats["Competence_3"] ?>, <?= $touslescandidats["Competence_4"] ?>, <?= $touslescandidats["Competence_5"] ?>, <?= $touslescandidats["Competence_6"] ?>, <?= $touslescandidats["Competence_7"] ?>, <?= $touslescandidats["Competence_8"] ?>, <?= $touslescandidats["Competence_9"] ?>, <?= $touslescandidats["Competence_10"] ?>'>
                            </div>

                            <div class="flex flex-row w-[90%] mb-5">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    <i class="fa-solid fa-globe"></i>
                                </span>
                                <input type="text" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="s1" value="<?= $touslescandidats["Site_Web"] ?>">
                                <div class="flex flex-row items-center">
                                    <a href="<?= $touslescandidats["Site_Web"] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Visiter le site web" target="_blank" class="fa-solid fa-right-long fa-2xl"></i></a>
                                </div>
                            </div>

                            <div class="flex flex-row w-[90%] mb-5">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    <i class="fa-solid fa-globe"></i>
                                </span>
                                <input type="text" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="s2" value="<?= $touslescandidats["Profil_Linkedin"] ?>">
                                <div class="flex flex-row items-center">
                                    <a href="<?= $touslescandidats["Site_Web"] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Visiter le site web" target="_blank" class="fa-solid fa-right-long fa-2xl"></i></a>
                                </div>
                            </div>

                            <div class="flex flex-row w-[90%] mb-5">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    <i class="fa-solid fa-globe"></i>
                                </span>
                                <input type="text" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="s3" value="<?= $touslescandidats["Profil_Viadeo"] ?>">
                                <div class="flex flex-row items-center">
                                    <a href="<?= $touslescandidats["Site_Web"] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Visiter le site web" target="_blank" class="fa-solid fa-right-long fa-2xl"></i></a>
                                </div>
                            </div>

                            <div class="flex flex-row w-[90%] mb-5">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                    <i class="fa-solid fa-globe"></i>
                                </span>
                                <input type="text" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="s4" value="<?= $touslescandidats["Profil_facebook"] ?>">
                                <div class="flex flex-row items-center">
                                    <a href="<?= $touslescandidats["Site_Web"] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Visiter le site web" target="_blank" class="fa-solid fa-right-long fa-2xl"></i></a>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center w-100">
                                <button type="submit" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800" name="sauces_profil">Mettre à jour mon profil</button>
                            </div>
                        </form>

                    </article>

                    <hr class="w-48 h-1 mx-auto my-4 bg-gray-400 border-0 rounded md:my-10 dark:bg-gray-700">

                    <article class="w-[50%]">

                        <form method="post" enctype="multipart/form-data" class="flex flex-col items-center">
                            <div class="md:w-[90%]">
                                <input class="md:w-[90%] block text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="file_input_help" id="file_input" type="file" name="monCV">
                                <p class=" mt-1 text-sm text-gray-500 dark:text-gray-300 mb-5 " id="file_input_help">PDF, DOCX.</p>
                            </div>
                            <div class="flex flex-col lg:flex-row gap-5">
                                <button type="submit" class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800" name="upCV">Mettre à jour mon CV</button>
                                <a href="<?= $touslescandidats["CV"] ?>" target="_blank"><button type="button" class="w-[180px] text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800" name="upCV">Voir mon CV</button></a>
                            </div>
                        </form>
                    </article>

                </section>
        <?php }
        }
        $CV->deco(); ?>