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

    // ── Shared Unsplash image URLs ─────────────────────────────────
    private const IMG = 'https://images.unsplash.com/photo-';
    private const Q   = '?w=800&q=80&fit=crop&auto=format';

    private function img(string $id): string
    {
        return self::IMG . $id . self::Q;
    }

    public function run(): void
    {
        Announcement::query()->delete();

        // ── Create 3 demo fournisseurs ──────────────────────────────
        $fournisseurs = $this->createFournisseurs();

        // ── Fetch category / subcategory IDs ───────────────────────
        $cats = Category::all()->keyBy('slug');
        $subs = Subcategory::all()->keyBy('slug');

        // ── Products ───────────────────────────────────────────────
        $products = [

            // ── Meubles de salon ──────────────────────────────────
            [
                'fournisseur' => 0,
                'title'       => 'Canapé 3 places en velours bleu pétrole',
                'description' => "Canapé 3 places moderne en velours doux, structure en bois massif de hêtre. Pieds en métal brossé doré. Idéal pour un salon élégant. Livraison possible sur tout le territoire.\n\nDimensions : L 210 × P 85 × H 82 cm",
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
                    $this->img('1493666438817-9b4a94bb7b3a'),
                    $this->img('1484101403633-562f891dc89a'),
                ],
            ],
            [
                'fournisseur' => 0,
                'title'       => 'Canapé d\'angle convertible gris anthracite',
                'description' => "Canapé d'angle panoramique avec méridienne côté gauche. Tissu microfibre ultra-résistant, facile à nettoyer. Convertible en lit d'appoint, coffre de rangement intégré.\n\nDimensions : L 270 × P 170 × H 78 cm",
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
                    $this->img('1555041469-a586c61ea9bc'),
                ],
            ],
            [
                'fournisseur' => 1,
                'title'       => 'Fauteuil bergère capitonné beige',
                'description' => "Fauteuil de style classique, revêtement en tissu beige clair, pieds en bois sculpté vernis noyer. Très confortable avec son rembourrage haute densité.\n\nDimensions : L 75 × P 80 × H 95 cm",
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
                    $this->img('1567538096630-e0c55bd6374c'),
                ],
            ],
            [
                'fournisseur' => 0,
                'title'       => 'Table basse en marbre blanc et métal noir',
                'description' => "Table basse design plateau en marbre véritable de Carrare, épaisseur 15 mm. Structure en métal laqué noir mat. Un meuble statement pour votre salon.\n\nDimensions : ∅ 80 × H 42 cm",
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
                ],
            ],
            [
                'fournisseur' => 2,
                'title'       => 'Meuble TV 160 cm chêne naturel',
                'description' => "Meuble TV en bois plaqué chêne, deux portes à poussoir sans poignée, deux niches ouvertes. Finition mate naturelle. Compatible TV jusqu'à 75 pouces.\n\nDimensions : L 160 × P 40 × H 50 cm",
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
                'fournisseur' => 1,
                'title'       => 'Bibliothèque murale modulable blanche 5 étagères',
                'description' => "Bibliothèque modulable en MDF laqué blanc brillant, 5 étagères ajustables. Fixation murale incluse, chevilles et vis comprises. Charge max par étagère : 20 kg.\n\nDimensions : L 90 × P 25 × H 180 cm",
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

            // ── Chambre ───────────────────────────────────────────
            [
                'fournisseur' => 0,
                'title'       => 'Lit 160×200 cm avec tête de lit capitonnée grise',
                'description' => "Lit double en bois massif de pin avec tête de lit capitonnée en tissu gris clair. Sommier à lattes inclus. Coffre de rangement optionnel disponible.\n\nDimensions du couchage : 160 × 200 cm. H tête de lit : 120 cm.",
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
                'fournisseur' => 2,
                'title'       => 'Armoire 3 portes coulissantes miroir chêne',
                'description' => "Armoire 3 portes coulissantes dont une porte miroir. Structure en panneaux particules effet chêne naturel. Intérieur : 2 penderies, 4 étagères, 3 tiroirs.\n\nDimensions : L 200 × P 60 × H 220 cm",
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
                    $this->img('1631679706909-1972bec3c06f'),
                ],
            ],
            [
                'fournisseur' => 1,
                'title'       => 'Matelas ressorts ensachés 140×190 — Confort Ferme',
                'description' => "Matelas à ressorts ensachés 7 zones de soutien, garnissage mousse mémoire de forme 4 cm + latex naturel 3 cm. Tissu respirant anti-acariens. Garantie 10 ans.\n\nDimensions : 140 × 190 cm, épaisseur 26 cm",
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
                    $this->img('1505693416388-ac5ce068fe85'),
                ],
            ],
            [
                'fournisseur' => 0,
                'title'       => 'Commode 6 tiroirs style scandinave blanc/bois',
                'description' => "Commode 6 tiroirs en MDF laqué blanc avec façades en bois de frêne naturel. Coulissants à glissement doux avec amortisseurs. Structure solide, montage facile.\n\nDimensions : L 90 × P 42 × H 82 cm",
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
                    $this->img('1505693416388-ac5ce068fe85'),
                ],
            ],

            // ── Salle à manger ────────────────────────────────────
            [
                'fournisseur' => 2,
                'title'       => 'Table à manger extensible 140-200 cm chêne massif',
                'description' => "Table à manger extensible en chêne massif huilé, rallonge de 60 cm intégrée. Pieds en bois massif tours. S'étend de 140 cm à 200 cm pour accueillir 4 à 8 personnes.\n\nDimensions : L 140-200 × P 90 × H 76 cm",
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
                'fournisseur' => 1,
                'title'       => 'Chaises salle à manger velours vert — lot de 4',
                'description' => "Lot de 4 chaises design assise en velours côtelé vert sauge, pieds en métal doré. Très tendance, confortables, assise rembourrée haute densité.\n\nDimensions par chaise : L 45 × P 50 × H 82 cm, assise H 46 cm",
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
                    $this->img('1493663284031-b7e3aefcae8e'),
                ],
            ],

            // ── Bureau ────────────────────────────────────────────
            [
                'fournisseur' => 0,
                'title'       => 'Bureau d\'angle en L bois blanc et gris',
                'description' => "Bureau d'angle en L, structure en panneaux MDF blanc avec plateau gris anthracite. Comprend un caisson 3 tiroirs sur roulettes. Câbles gérés par passe-câble intégré.\n\nDimensions : L 160 × P 120 × H 75 cm",
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

            // ── Luminaires ────────────────────────────────────────
            [
                'fournisseur' => 2,
                'title'       => 'Lustre suspension 6 branches style industriel noir',
                'description' => "Lustre à 6 branches ajustables, structure en métal laqué noir mat, ampoules E27 (non incluses). Câble tressé 1,5 m. Parfait pour salle à manger ou salon loft.\n\nDimensions : ∅ 65 × H réglable jusqu'à 120 cm",
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
                    $this->img('1524484485831-a92ffc0de03f'),
                ],
            ],
            [
                'fournisseur' => 1,
                'title'       => 'Lampe de sol arc dorée avec abat-jour crème',
                'description' => "Lampe de sol arc en métal doré brossé, abat-jour en coton écru. Structure en laiton massif, base en marbre naturel pour une stabilité optimale. E27, 40W max.\n\nHauteur totale : 190 cm. Ø abat-jour : 55 cm",
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
                    $this->img('1558618666-fcd25c85cd64'),
                ],
            ],

            // ── Accessoires & Déco ────────────────────────────────
            [
                'fournisseur' => 0,
                'title'       => 'Miroir rond doré ∅ 80 cm style Art Déco',
                'description' => "Miroir rond avec cadre en métal doré satinée, motif rayonnant style Art Déco. Verre argenté 5 mm. Fixation murale horizontale ou verticale. Livré avec le kit de fixation.\n\nDimensions : ∅ 80 cm, épaisseur cadre 3 cm",
                'price'       => 320.000,
                'condition'   => 'new',
                'marque'      => 'GoldDeco',
                'dimensions'  => 'Ø 80 cm',
                'ville'       => 'Tunis',
                'livraison'   => true,
                'category'    => 'accessoires',
                'subcategory' => 'miroirs',
                'views'       => 276,
                'images'      => [
                    $this->img('1586023492125-27b2c045efd7'),
                ],
            ],
            [
                'fournisseur' => 2,
                'title'       => 'Tapis berbère laine naturelle 160×230 cm',
                'description' => "Tapis de style berbère tissé à la main en pure laine naturelle, motifs géométriques traditionnels. Coloris ivoire, noir et terra cotta. Antidérapant, résistant.\n\nDimensions : 160 × 230 cm, épaisseur 12 mm",
                'price'       => 1200.000,
                'condition'   => 'new',
                'marque'      => 'ArtNomade',
                'dimensions'  => '160 × 230 cm',
                'ville'       => 'Sousse',
                'livraison'   => true,
                'category'    => 'accessoires',
                'subcategory' => 'tapis',
                'views'       => 390,
                'images'      => [
                    $this->img('1506440027765-4fbf89cfe8e0'),
                ],
            ],
            [
                'fournisseur' => 1,
                'title'       => 'Ensemble 4 coussins déco velours terracotta',
                'description' => "Lot de 4 coussins décoratifs en velours terracotta, fermeture zippée cachée. Rembourrage en fibres de polyester. Lavable en machine à 30°.\n\nDimensions : 45 × 45 cm",
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
                'fournisseur' => 0,
                'title'       => 'Vase céramique fait main terracotta 35 cm',
                'description' => "Vase artisanal en céramique émaillée terracotta, fabriqué à la main à Nabeul. Chaque pièce est unique. Idéal pour branches séchées ou fleurs fraîches.\n\nHauteur : 35 cm, ∅ ouverture : 12 cm",
                'price'       => 95.000,
                'condition'   => 'new',
                'marque'      => 'Nabeul Craft',
                'dimensions'  => 'H 35 cm, Ø 12 cm',
                'ville'       => 'Tunis',
                'livraison'   => false,
                'category'    => 'accessoires',
                'subcategory' => 'vases',
                'views'       => 87,
                'images'      => [
                    $this->img('1586023492125-27b2c045efd7'),
                ],
            ],

            // ── Occasion ──────────────────────────────────────────
            [
                'fournisseur' => 2,
                'title'       => 'Canapé 2 places cuir marron — très bon état',
                'description' => "Canapé 2 places en cuir véritable marron tabac, très bon état général, quelques micro-rayures invisibles à l'usage. Vendu car déménagement. Structure bois solide.\n\nDimensions : L 155 × P 80 × H 85 cm",
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
                    $this->img('1493666438817-9b4a94bb7b3a'),
                ],
            ],
            [
                'fournisseur' => 1,
                'title'       => 'Table en verre trempé + 6 chaises — occasion',
                'description' => "Ensemble salle à manger : table en verre trempé 180 cm sur pieds chromés + 6 chaises en tissu gris. Bon état général, chaises recouvertes récemment. Visible sur place à Sfax.\n\nDimensions table : L 180 × P 90 × H 75 cm",
                'price'       => 1100.000,
                'condition'   => 'used',
                'marque'      => null,
                'dimensions'  => 'L 180 × P 90 × H 75 cm',
                'ville'       => 'Sfax',
                'livraison'   => false,
                'category'    => 'meubles',
                'subcategory' => 'meubles-salle-manger',
                'views'       => 312,
                'images'      => [
                    $this->img('1493663284031-b7e3aefcae8e'),
                    $this->img('1503602642458-232111c1f7ce'),
                ],
            ],
            [
                'fournisseur' => 0,
                'title'       => 'Lit 180×200 avec matelas — état comme neuf',
                'description' => "Lit king size avec matelas ressorts, utilisé 6 mois seulement. Tête de lit en bois noir, sommier à lattes inclus. Matelas en parfait état, sans taches.\n\nDimensions : 180 × 200 cm",
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
                    $this->img('1631679706909-1972bec3c06f'),
                    $this->img('1505693416388-ac5ce068fe85'),
                ],
            ],
        ];

        foreach ($products as $data) {
            $fId = $fournisseurs[$data['fournisseur']]->id;
            $cat = isset($data['category']) ? $cats->get($data['category']) : null;
            $sub = isset($data['subcategory']) ? $subs->get($data['subcategory']) : null;

            $slug = Str::slug($data['title']);
            $i = 1;
            while (Announcement::where('slug', $slug)->exists()) {
                $slug = Str::slug($data['title']) . '-' . $i++;
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

        $this->command->info('✓ ' . count($products) . ' annonces créées avec succès.');
    }

    private function createFournisseurs(): array
    {
        $demoFournisseurs = [
            [
                'email'          => 'casa.moderna@demo.tn',
                'nom_entreprise' => 'Casa Moderna',
            ],
            [
                'email'          => 'maison.plus@demo.tn',
                'nom_entreprise' => 'Maison Plus',
            ],
            [
                'email'          => 'scandiwood@demo.tn',
                'nom_entreprise' => 'Scandiwood',
            ],
        ];

        $result = [];
        foreach ($demoFournisseurs as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'password' => Hash::make('demo1234'),
                    'role'     => 'fournisseur',
                    'ville'    => 'Tunis',
                ]
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
