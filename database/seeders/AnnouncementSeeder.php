<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\Fournisseur;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AnnouncementSeeder extends Seeder
{
    use WithoutModelEvents;

    // ID Unsplash vérifié
    private function img(string $id): string
    {
        return "https://images.unsplash.com/photo-{$id}?w=800&q=80&fit=crop&auto=format";
    }

    // Pexels : images stables avec ID vérifié, correspondant au sujet
    private function pexels(int $id): string
    {
        return "https://images.pexels.com/photos/{$id}/pexels-photo-{$id}.jpeg?auto=compress&cs=tinysrgb&w=800";
    }

    public function run(): void
    {
        Announcement::query()->delete();

        $fournisseurs = $this->createFournisseurs();
        $cats = Category::all()->keyBy('slug');
        $subs = Subcategory::all()->keyBy('slug');

        $products = [

            // ═══════════════════════════════════
            // MEUBLES
            // ═══════════════════════════════════

            [
                'fournisseur' => 0,
                'title'       => 'Canapé 3 places velours bleu pétrole',
                'description' => "Canapé 3 places en velours doux, structure bois massif de hêtre, pieds métal brossé doré. Parfait pour un salon élégant.\n\nDimensions : L 210 × P 85 × H 82 cm",
                'price'       => 1490.000,
                'condition'   => 'new',
                'marque'      => 'Casa Moderna',
                'dimensions'  => 'L 210 × P 85 × H 82 cm',
                'ville'       => 'Tunis',
                'livraison'   => true,
                'category'    => 'meubles',
                'subcategory' => 'canapes',
                'views'       => 312,
                'images'      => [
                    $this->img('1555041469-a586c61ea9bc'),
                    $this->img('1567538096630-e0c55bd6374c'),
                ],
            ],
            [
                'fournisseur' => 1,
                'title'       => "Canapé d'angle panoramique gris anthracite",
                'description' => "Canapé d'angle avec méridienne côté gauche, tissu microfibre ultra-résistant. Convertible lit d'appoint, coffre de rangement intégré.\n\nDimensions : L 270 × P 170 × H 78 cm",
                'price'       => 2150.000,
                'condition'   => 'new',
                'marque'      => 'BelEspace',
                'dimensions'  => 'L 270 × P 170 × H 78 cm',
                'ville'       => 'Tunis',
                'livraison'   => true,
                'category'    => 'meubles',
                'subcategory' => 'canapes',
                'views'       => 245,
                'images'      => [
                    $this->img('1493666438817-9b4a94bb7b3a'),
                ],
            ],
            [
                'fournisseur' => 2,
                'title'       => 'Fauteuil bergère capitonné beige',
                'description' => "Fauteuil de style classique, tissu beige clair, pieds bois sculpté vernis noyer. Rembourrage haute densité, très confortable.\n\nDimensions : L 75 × P 80 × H 95 cm",
                'price'       => 750.000,
                'condition'   => 'new',
                'marque'      => 'MaisonPlus',
                'dimensions'  => 'L 75 × P 80 × H 95 cm',
                'ville'       => 'Sfax',
                'livraison'   => false,
                'category'    => 'meubles',
                'subcategory' => 'fauteuils',
                'views'       => 178,
                'images'      => [
                    $this->pexels(5490303), // Interior of living room with sofa and armchair
                ],
            ],
            [
                'fournisseur' => 0,
                'title'       => 'Table basse plateau marbre blanc et métal noir',
                'description' => "Table basse plateau marbre véritable de Carrare épaisseur 15 mm, structure métal laqué noir mat. Un meuble statement pour votre salon.\n\nDimensions : Ø 80 × H 42 cm",
                'price'       => 980.000,
                'condition'   => 'new',
                'marque'      => 'NoblePiece',
                'dimensions'  => 'Ø 80 × H 42 cm',
                'ville'       => 'Tunis',
                'livraison'   => true,
                'category'    => 'meubles',
                'subcategory' => 'tables',
                'views'       => 421,
                'images'      => [
                    $this->img('1501045661006-fcebe0257c3f'),
                    $this->pexels(4846106), // Cozy living room with armchair and coffee table
                ],
            ],
            [
                'fournisseur' => 1,
                'title'       => 'Meuble TV 160 cm chêne naturel',
                'description' => "Meuble TV bois plaqué chêne, deux portes à poussoir, deux niches ouvertes. Compatible TV jusqu'à 75 pouces.\n\nDimensions : L 160 × P 40 × H 50 cm",
                'price'       => 1250.000,
                'condition'   => 'new',
                'marque'      => 'Scandiwood',
                'dimensions'  => 'L 160 × P 40 × H 50 cm',
                'ville'       => 'Sousse',
                'livraison'   => true,
                'category'    => 'meubles',
                'subcategory' => 'meubles-tv',
                'views'       => 367,
                'images'      => [
                    $this->img('1484101403633-562f891dc89a'),
                ],
            ],
            [
                'fournisseur' => 2,
                'title'       => 'Bibliothèque murale modulable blanche 5 étagères',
                'description' => "Bibliothèque MDF laqué blanc brillant, 5 étagères ajustables. Fixation murale incluse, charge max 20 kg par étagère.\n\nDimensions : L 90 × P 25 × H 180 cm",
                'price'       => 620.000,
                'condition'   => 'new',
                'marque'      => 'Modulo',
                'dimensions'  => 'L 90 × P 25 × H 180 cm',
                'ville'       => 'Sfax',
                'livraison'   => false,
                'category'    => 'meubles',
                'subcategory' => 'etageres',
                'views'       => 134,
                'images'      => [
                    $this->img('1481277542470-c1e3c9e32f23'),
                ],
            ],
            [
                'fournisseur' => 0,
                'title'       => 'Lit 160×200 cm tête de lit capitonnée grise',
                'description' => "Lit double bois massif pin, tête de lit capitonnée tissu gris clair. Sommier à lattes inclus. Coffre de rangement optionnel.\n\nDimensions : 160 × 200 cm. H tête de lit : 120 cm",
                'price'       => 1890.000,
                'condition'   => 'new',
                'marque'      => 'DreamBed',
                'dimensions'  => 'Couchage 160 × 200 cm',
                'ville'       => 'Tunis',
                'livraison'   => true,
                'category'    => 'meubles',
                'subcategory' => 'lits-literie',
                'views'       => 502,
                'images'      => [
                    $this->img('1631679706909-1972bec3c06f'),
                    $this->img('1505693416388-ac5ce068fe85'),
                ],
            ],
            [
                'fournisseur' => 1,
                'title'       => 'Armoire 3 portes coulissantes miroir chêne',
                'description' => "Armoire 3 portes coulissantes dont une porte miroir. Intérieur : 2 penderies, 4 étagères, 3 tiroirs.\n\nDimensions : L 200 × P 60 × H 220 cm",
                'price'       => 2450.000,
                'condition'   => 'new',
                'marque'      => 'Scandiwood',
                'dimensions'  => 'L 200 × P 60 × H 220 cm',
                'ville'       => 'Sousse',
                'livraison'   => true,
                'category'    => 'meubles',
                'subcategory' => 'armoires-placards',
                'views'       => 289,
                'images'      => [
                    $this->pexels(14547145), // TV and wardrobe with mirror in bedroom
                ],
            ],
            [
                'fournisseur' => 2,
                'title'       => 'Matelas ressorts ensachés 140×190 — Confort Ferme',
                'description' => "Matelas 7 zones de soutien, mousse mémoire 4 cm + latex naturel 3 cm. Tissu respirant anti-acariens. Garantie 10 ans.\n\nDimensions : 140 × 190 × 26 cm",
                'price'       => 1650.000,
                'condition'   => 'new',
                'marque'      => 'SleepWell',
                'dimensions'  => '140 × 190 × 26 cm',
                'ville'       => 'Sfax',
                'livraison'   => true,
                'category'    => 'meubles',
                'subcategory' => 'matelas',
                'views'       => 198,
                'images'      => [
                    $this->pexels(376531), // Brown bed and white mattress
                ],
            ],
            [
                'fournisseur' => 0,
                'title'       => 'Commode 6 tiroirs style scandinave blanc/bois',
                'description' => "Commode 6 tiroirs MDF laqué blanc avec façades frêne naturel. Coulissants à amortisseurs.\n\nDimensions : L 90 × P 42 × H 82 cm",
                'price'       => 780.000,
                'condition'   => 'new',
                'marque'      => 'NordicHome',
                'dimensions'  => 'L 90 × P 42 × H 82 cm',
                'ville'       => 'Tunis',
                'livraison'   => false,
                'category'    => 'meubles',
                'subcategory' => 'commodes',
                'views'       => 156,
                'images'      => [
                    $this->pexels(8135253), // White vintage chest of drawers
                ],
            ],
            [
                'fournisseur' => 1,
                'title'       => 'Table à manger extensible 140–200 cm chêne massif',
                'description' => "Table extensible chêne massif huilé, rallonge 60 cm intégrée. De 4 à 8 personnes.\n\nDimensions : L 140-200 × P 90 × H 76 cm",
                'price'       => 3200.000,
                'condition'   => 'new',
                'marque'      => 'ChêneRoyal',
                'dimensions'  => 'L 140-200 × P 90 × H 76 cm',
                'ville'       => 'Sousse',
                'livraison'   => true,
                'category'    => 'meubles',
                'subcategory' => 'meubles-salle-manger',
                'views'       => 334,
                'images'      => [
                    $this->img('1493663284031-b7e3aefcae8e'),
                ],
            ],
            [
                'fournisseur' => 2,
                'title'       => 'Chaises salle à manger velours vert — lot de 4',
                'description' => "Lot de 4 chaises velours côtelé vert sauge, pieds métal doré. Assise rembourrée haute densité.\n\nDimensions : L 45 × P 50 × H 82 cm",
                'price'       => 960.000,
                'condition'   => 'new',
                'marque'      => 'MaisonPlus',
                'dimensions'  => 'L 45 × P 50 × H 82 cm (×4)',
                'ville'       => 'Sfax',
                'livraison'   => false,
                'category'    => 'meubles',
                'subcategory' => 'chaises',
                'views'       => 211,
                'images'      => [
                    $this->img('1503602642458-232111c1f7ce'),
                    $this->pexels(1581384), // Restaurant table and chairs
                ],
            ],
            [
                'fournisseur' => 0,
                'title'       => "Bureau d'angle en L blanc et gris",
                'description' => "Bureau d'angle L, MDF blanc + plateau gris anthracite. Caisson 3 tiroirs sur roulettes. Passe-câble intégré.\n\nDimensions : L 160 × P 120 × H 75 cm",
                'price'       => 1100.000,
                'condition'   => 'new',
                'marque'      => 'WorkSpace',
                'dimensions'  => 'L 160 × P 120 × H 75 cm',
                'ville'       => 'Tunis',
                'livraison'   => true,
                'category'    => 'meubles',
                'subcategory' => 'mobilier-bureau',
                'views'       => 267,
                'images'      => [
                    $this->img('1593642632559-0c6d3fc62b89'),
                ],
            ],

            // ═══════════════════════════════════
            // LUMINAIRES
            // ═══════════════════════════════════

            [
                'fournisseur' => 1,
                'title'       => 'Lustre suspension 6 branches style industriel noir',
                'description' => "Lustre 6 branches ajustables, métal laqué noir mat, ampoules E27 (non incluses), câble tressé 1,5 m.\n\nDimensions : Ø 65 cm, H réglable 120 cm",
                'price'       => 450.000,
                'condition'   => 'new',
                'marque'      => 'LightFactory',
                'dimensions'  => 'Ø 65 cm, H réglable 120 cm',
                'ville'       => 'Sousse',
                'livraison'   => true,
                'category'    => 'luminaires',
                'subcategory' => 'lustres-suspensions',
                'views'       => 188,
                'images'      => [
                    $this->img('1558618666-fcd25c85cd64'),
                ],
            ],
            [
                'fournisseur' => 2,
                'title'       => 'Lampe de sol arc dorée abat-jour crème',
                'description' => "Lampe arc métal doré brossé, abat-jour coton écru, base en marbre naturel. E27, 40W max.\n\nHauteur : 190 cm. Ø abat-jour : 55 cm",
                'price'       => 380.000,
                'condition'   => 'new',
                'marque'      => 'LuceDesign',
                'dimensions'  => 'H 190 cm, Ø abat-jour 55 cm',
                'ville'       => 'Sfax',
                'livraison'   => false,
                'category'    => 'luminaires',
                'subcategory' => 'lampes-sol',
                'views'       => 143,
                'images'      => [
                    $this->img('1524484485831-a92ffc0de03f'),
                ],
            ],
            [
                'fournisseur' => 0,
                'title'       => 'Lampe de table céramique ocre avec abat-jour lin',
                'description' => "Lampe de table pied en céramique émaillée ocre, abat-jour conique en lin naturel. Câble tissu torsadé. E14, 25W.\n\nHauteur totale : 50 cm",
                'price'       => 195.000,
                'condition'   => 'new',
                'marque'      => 'TerraLuce',
                'dimensions'  => 'H 50 cm, Ø base 15 cm',
                'ville'       => 'Tunis',
                'livraison'   => true,
                'category'    => 'luminaires',
                'subcategory' => 'lampes-table',
                'views'       => 97,
                'images'      => [
                    $this->pexels(5849392), // Room interior with lamp and vases on table
                ],
            ],
            [
                'fournisseur' => 1,
                'title'       => 'Applique murale LED marbre blanc et laiton',
                'description' => "Applique murale plateau en marbre blanc, structure laiton brossé. LED 8W intégré, lumière chaude 3000K.\n\nDimensions : L 25 × P 12 × H 18 cm",
                'price'       => 285.000,
                'condition'   => 'new',
                'marque'      => 'MarbleLux',
                'dimensions'  => 'L 25 × P 12 × H 18 cm',
                'ville'       => 'Sousse',
                'livraison'   => true,
                'category'    => 'luminaires',
                'subcategory' => 'appliques-murales',
                'views'       => 74,
                'images'      => [
                    $this->pexels(631411), // Turned-on lamp sconces on wall
                ],
            ],

            // ═══════════════════════════════════
            // ACCESSOIRES
            // ═══════════════════════════════════

            [
                'fournisseur' => 2,
                'title'       => 'Miroir rond doré Ø 80 cm style Art Déco',
                'description' => "Miroir rond cadre métal doré satiné, motif rayonnant Art Déco. Verre argenté 5 mm. Kit de fixation inclus.\n\nDimensions : Ø 80 cm",
                'price'       => 320.000,
                'condition'   => 'new',
                'marque'      => 'GoldDeco',
                'dimensions'  => 'Ø 80 cm',
                'ville'       => 'Sousse',
                'livraison'   => true,
                'category'    => 'accessoires',
                'subcategory' => 'miroirs',
                'views'       => 276,
                'images'      => [
                    $this->img('1586023492125-27b2c045efd7'),
                ],
            ],
            [
                'fournisseur' => 0,
                'title'       => 'Tapis berbère laine naturelle 160×230 cm',
                'description' => "Tapis berbère tissé main en pure laine naturelle, motifs géométriques. Coloris ivoire, noir et terra cotta. Antidérapant.\n\nDimensions : 160 × 230 cm, épaisseur 12 mm",
                'price'       => 1200.000,
                'condition'   => 'new',
                'marque'      => 'ArtNomade',
                'dimensions'  => '160 × 230 cm',
                'ville'       => 'Tunis',
                'livraison'   => true,
                'category'    => 'accessoires',
                'subcategory' => 'tapis',
                'views'       => 390,
                'images'      => [
                    $this->img('1506440027765-4fbf89cfe8e0'),
                    $this->pexels(33961431), // Traditional Moroccan rugs
                ],
            ],
            [
                'fournisseur' => 1,
                'title'       => 'Lot 4 coussins déco velours terracotta',
                'description' => "Lot de 4 coussins velours terracotta, fermeture zippée. Lavable machine à 30°. Garnissage fibres polyester.\n\nDimensions : 45 × 45 cm",
                'price'       => 180.000,
                'condition'   => 'new',
                'marque'      => 'CasaTextile',
                'dimensions'  => '45 × 45 cm (×4)',
                'ville'       => 'Sfax',
                'livraison'   => true,
                'category'    => 'accessoires',
                'subcategory' => 'coussins',
                'views'       => 122,
                'images'      => [
                    $this->img('1540546276925-6b42e6b34124'),
                ],
            ],
            [
                'fournisseur' => 2,
                'title'       => 'Vase céramique artisanal terracotta 35 cm',
                'description' => "Vase artisanal céramique émaillée terracotta, fabriqué à Nabeul. Pièce unique. Idéal branches séchées ou fleurs fraîches.\n\nH 35 cm, Ø ouverture 12 cm",
                'price'       => 95.000,
                'condition'   => 'new',
                'marque'      => 'Nabeul Craft',
                'dimensions'  => 'H 35 cm, Ø 12 cm',
                'ville'       => 'Sousse',
                'livraison'   => false,
                'category'    => 'accessoires',
                'subcategory' => 'vases',
                'views'       => 87,
                'images'      => [
                    $this->pexels(3733769), // Dried plants in ceramic vase on wooden stool
                ],
            ],
            [
                'fournisseur' => 0,
                'title'       => 'Tableau abstrait toile 80×120 cm — édition limitée',
                'description' => "Toile d'art abstrait peinte à la main, tons ocre, beige et noir. Édition limitée signée. Livré avec système d'accrochage.\n\nDimensions : 80 × 120 cm",
                'price'       => 450.000,
                'condition'   => 'new',
                'marque'      => 'ArtStudio Tunis',
                'dimensions'  => '80 × 120 cm',
                'ville'       => 'Tunis',
                'livraison'   => true,
                'category'    => 'accessoires',
                'subcategory' => 'tableaux-art-mural',
                'views'       => 203,
                'images'      => [
                    $this->pexels(3987574), // Abstract art painting on canvas
                ],
            ],
            [
                'fournisseur' => 1,
                'title'       => 'Horloge murale industrielle Ø 60 cm métal noir',
                'description' => "Horloge murale cadre métal laqué noir, aiguilles dorées, chiffres romains relief. Mécanisme à quartz silencieux. Pile AA incluse.\n\nDiamètre : 60 cm",
                'price'       => 220.000,
                'condition'   => 'new',
                'marque'      => 'TimeCo',
                'dimensions'  => 'Ø 60 cm',
                'ville'       => 'Sfax',
                'livraison'   => true,
                'category'    => 'accessoires',
                'subcategory' => 'horloges',
                'views'       => 118,
                'images'      => [
                    $this->pexels(1010480), // Round black analog wall clock
                ],
            ],

            // ═══════════════════════════════════
            // JARDIN & LOISIRS
            // ═══════════════════════════════════

            [
                'fournisseur' => 2,
                'title'       => 'Salon de jardin rotin synthétique 4 places + table basse',
                'description' => "Salon jardin composé de 1 canapé 2 places, 2 fauteuils et 1 table basse. Rotin tressé main, résistant aux UV et aux intempéries. Coussins imperméables inclus.\n\nTable : L 100 × P 60 × H 35 cm",
                'price'       => 2800.000,
                'condition'   => 'new',
                'marque'      => 'OutdoorLiving',
                'dimensions'  => 'Canapé : L 155 × P 70 × H 70 cm',
                'ville'       => 'Sousse',
                'livraison'   => true,
                'category'    => 'jardin-loisirs',
                'subcategory' => 'mobilier-jardin',
                'views'       => 341,
                'images'      => [
                    $this->pexels(6430742), // Cozy rattan sofa with cushions in tropical garden
                    $this->pexels(1843655), // Table dining set up outdoor
                ],
            ],
            [
                'fournisseur' => 0,
                'title'       => 'Table de jardin aluminium extensible 160-220 cm',
                'description' => "Table de jardin aluminium anodisé gris anthracite, rallonge 60 cm intégrée. 6 à 8 personnes. Résistante à la rouille, entretien facile.\n\nDimensions : L 160-220 × P 90 × H 75 cm",
                'price'       => 1650.000,
                'condition'   => 'new',
                'marque'      => 'AluminiumDéco',
                'dimensions'  => 'L 160-220 × P 90 × H 75 cm',
                'ville'       => 'Tunis',
                'livraison'   => true,
                'category'    => 'jardin-loisirs',
                'subcategory' => 'mobilier-jardin',
                'views'       => 189,
                'images'      => [
                    $this->pexels(11021595), // Outdoor table set for meal
                ],
            ],
            [
                'fournisseur' => 1,
                'title'       => 'Transat bain de soleil bois teck naturel',
                'description' => "Transat bain de soleil en bois de teck certifié FSC, dossier réglable 5 positions, roues pour déplacement facile. Matelas non inclus.\n\nDimensions : L 190 × P 60 × H 30 cm",
                'price'       => 890.000,
                'condition'   => 'new',
                'marque'      => 'TeckNatura',
                'dimensions'  => 'L 190 × P 60 × H 30 cm',
                'ville'       => 'Sfax',
                'livraison'   => false,
                'category'    => 'jardin-loisirs',
                'subcategory' => 'mobilier-lounge',
                'views'       => 156,
                'images'      => [
                    $this->pexels(2771921), // Sun loungers on the deck
                ],
            ],
            [
                'fournisseur' => 2,
                'title'       => 'Parasol déporté Ø 3 m toile imperméable grise',
                'description' => "Parasol à mât déporté Ø 3 m, toile polyester imperméable et anti-UV, manivelle pour ouverture facile. Pied lestable en fonte inclus.\n\nDiamètre : 3 m, mât aluminium",
                'price'       => 680.000,
                'condition'   => 'new',
                'marque'      => 'SunShade',
                'dimensions'  => 'Ø 3 m',
                'ville'       => 'Sousse',
                'livraison'   => true,
                'category'    => 'jardin-loisirs',
                'subcategory' => 'protections-solaires',
                'views'       => 224,
                'images'      => [
                    $this->pexels(1154638), // Woman sitting on armchair under white patio umbrella
                ],
            ],
            [
                'fournisseur' => 0,
                'title'       => 'Plancha gaz 4 brûleurs inox — intégrée ou posée',
                'description' => "Plancha gaz 4 brûleurs en inox 304 brossé. Puissance 12 kW. Compatible propane/butane. Grille de récupération amovible. Montée en température rapide.\n\nDimensions : L 70 × P 50 cm",
                'price'       => 1450.000,
                'condition'   => 'new',
                'marque'      => 'GrillMaster',
                'dimensions'  => 'L 70 × P 50 cm',
                'ville'       => 'Tunis',
                'livraison'   => true,
                'category'    => 'jardin-loisirs',
                'subcategory' => 'barbecues',
                'views'       => 298,
                'images'      => [
                    $this->pexels(7893772), // Empty barbecue grill standing in the backyard
                ],
            ],

            // ═══════════════════════════════════
            // TEXTILES
            // ═══════════════════════════════════

            [
                'fournisseur' => 1,
                'title'       => 'Parure de lit satin coton 200×200 cm blanc cassé',
                'description' => "Parure de lit satin de coton 300 fils, blanc ivoire. Comprend : 1 housse couette 200×200, 2 taies 65×65 cm. Lavable à 60°.\n\nMatière : 100% coton satin",
                'price'       => 285.000,
                'condition'   => 'new',
                'marque'      => 'LinenHome',
                'dimensions'  => 'Housse 200×200 cm + 2 taies',
                'ville'       => 'Sfax',
                'livraison'   => true,
                'category'    => 'textiles',
                'subcategory' => 'linge-de-lit',
                'views'       => 167,
                'images'      => [
                    $this->pexels(57686),    // White bed linen
                    $this->pexels(10061393), // Bed with white pillow and creased linen
                ],
            ],
            [
                'fournisseur' => 2,
                'title'       => 'Rideaux lin lavé 2×140×280 cm gris taupe',
                'description' => "Paire de rideaux lin lavé gris taupe à œillets inox. Effet aérien et naturel, occultation partielle. Lavable à 30°. Sold par paire.\n\nDimensions : 2 panneaux de 140×280 cm",
                'price'       => 320.000,
                'condition'   => 'new',
                'marque'      => 'LinenArt',
                'dimensions'  => '2 × L 140 × H 280 cm',
                'ville'       => 'Sousse',
                'livraison'   => true,
                'category'    => 'textiles',
                'subcategory' => 'rideaux',
                'views'       => 142,
                'images'      => [
                    $this->pexels(14688211), // Curtains on the glass window
                ],
            ],
            [
                'fournisseur' => 0,
                'title'       => 'Plaid mohair laine mélangée 130×180 cm caramel',
                'description' => "Plaid douillet mohair et laine mélangée, coloris caramel chaud. Très doux au toucher, chaud sans être lourd. Frange tressée sur les bords.\n\nDimensions : 130 × 180 cm",
                'price'       => 145.000,
                'condition'   => 'new',
                'marque'      => 'WoolCasa',
                'dimensions'  => '130 × 180 cm',
                'ville'       => 'Tunis',
                'livraison'   => true,
                'category'    => 'textiles',
                'subcategory' => 'couvertures-plaids',
                'views'       => 98,
                'images'      => [
                    $this->pexels(5806980), // Blanket with pattern on gray sofa
                ],
            ],
            [
                'fournisseur' => 1,
                'title'       => 'Housse de canapé élastique 3 places gris clair',
                'description' => "Housse de canapé 3 places en tissu stretch élastique gris clair. Universelle, s'adapte aux canapés L 180-230 cm. Lavable machine 40°. Anti-glissement intégré.\n\nConvient canapé L 180-230 cm",
                'price'       => 120.000,
                'condition'   => 'new',
                'marque'      => 'CoverHome',
                'dimensions'  => 'Pour canapé L 180-230 cm',
                'ville'       => 'Sfax',
                'livraison'   => true,
                'category'    => 'textiles',
                'subcategory' => 'housses-canape',
                'views'       => 76,
                'images'      => [
                    $this->pexels(30350512), // Cozy beige sofa with slipcover in modern living room
                ],
            ],

            // ═══════════════════════════════════
            // OCCASION — plusieurs catégories
            // ═══════════════════════════════════

            [
                'fournisseur' => 2,
                'title'       => 'Canapé 2 places cuir marron — très bon état',
                'description' => "Canapé 2 places cuir véritable marron tabac, très bon état, quelques micro-rayures invisibles. Vendu pour déménagement. Structure bois solide.\n\nDimensions : L 155 × P 80 × H 85 cm",
                'price'       => 680.000,
                'condition'   => 'like_new',
                'marque'      => 'Conforama',
                'dimensions'  => 'L 155 × P 80 × H 85 cm',
                'ville'       => 'Sousse',
                'livraison'   => false,
                'category'    => 'meubles',
                'subcategory' => 'canapes',
                'views'       => 445,
                'images'      => [
                    $this->pexels(18943248), // Living room with brown leather sofa and coffee table
                ],
            ],
            [
                'fournisseur' => 0,
                'title'       => 'Lit 180×200 + matelas — état comme neuf',
                'description' => "Lit king size avec matelas ressorts, utilisé 6 mois. Tête de lit bois noir, sommier lattes inclus. Matelas parfait état.\n\nDimensions : 180 × 200 cm",
                'price'       => 1400.000,
                'condition'   => 'like_new',
                'marque'      => 'SleepWell',
                'dimensions'  => '180 × 200 cm',
                'ville'       => 'Tunis',
                'livraison'   => true,
                'category'    => 'meubles',
                'subcategory' => 'lits-literie',
                'views'       => 523,
                'images'      => [
                    $this->pexels(12119234), // Spacious bedroom with king size bed
                ],
            ],
        ];

        foreach ($products as $data) {
            $fId = $fournisseurs[$data['fournisseur']]->id;
            $cat = $cats->get($data['category'] ?? '');
            $sub = $subs->get($data['subcategory'] ?? '');

            // Générer un slug unique (WithoutModelEvents désactive le booted() hook)
            $base = Str::slug($data['title']);
            $slug = $base;
            $i    = 1;
            while (Announcement::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }

            Announcement::create([
                'fournisseur_id' => $fId,
                'category_id'    => $cat?->id,
                'subcategory_id' => $sub?->id,
                'title'          => $data['title'],
                'slug'           => $slug,
                'description'    => $data['description'],
                'price'          => $data['price'],
                'condition'      => $data['condition'],
                'marque'         => $data['marque'] ?? null,
                'dimensions'     => $data['dimensions'] ?? null,
                'ville'          => $data['ville'] ?? null,
                'livraison'      => $data['livraison'] ?? false,
                'images'         => $data['images'] ?? [],
                'is_active'      => true,
                'views'          => $data['views'] ?? 0,
            ]);
        }

        $this->command->info('✓ ' . count($products) . ' annonces créées.');
    }

    private function createFournisseurs(): array
    {
        $demos = [
            ['email' => 'casa.moderna@demo.tn',  'nom_entreprise' => 'Casa Moderna'],
            ['email' => 'maison.plus@demo.tn',   'nom_entreprise' => 'Maison Plus'],
            ['email' => 'scandiwood@demo.tn',     'nom_entreprise' => 'Scandiwood'],
        ];

        $result = [];
        foreach ($demos as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['password' => Hash::make('demo1234'), 'role' => 'fournisseur', 'ville' => 'Tunis']
            );
            $fournisseur = Fournisseur::firstOrCreate(
                ['user_id' => $user->id],
                ['nom_entreprise' => $data['nom_entreprise'], 'statut' => 'actif']
            );
            $result[] = $fournisseur;
        }

        return $result;
    }
}
