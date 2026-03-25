<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Truncate before re-seeding to avoid unique constraint violations
        Subcategory::query()->delete();
        Category::query()->delete();

        $categories = [
            [
                'name' => 'Meubles',
                'slug' => 'meubles',
                'children' => [
                    ['name' => 'Canapés',                   'slug' => 'canapes'],
                    ['name' => 'Fauteuils',                 'slug' => 'fauteuils'],
                    ['name' => 'Chaises',                   'slug' => 'chaises'],
                    ['name' => 'Tables',                    'slug' => 'tables'],
                    ['name' => 'Lits & literie',            'slug' => 'lits-literie'],
                    ['name' => 'Armoires & placards',       'slug' => 'armoires-placards'],
                    ['name' => 'Bancs',                     'slug' => 'bancs'],
                    ['name' => 'Buffets hauts',             'slug' => 'buffets-hauts'],
                    ['name' => 'Tabourets',                 'slug' => 'tabourets'],
                    ['name' => 'Cheminées',                 'slug' => 'cheminees'],
                    ['name' => 'Commodes',                  'slug' => 'commodes'],
                    ['name' => 'Sommiers',                  'slug' => 'sommiers'],
                    ['name' => 'Matelas',                   'slug' => 'matelas'],
                    ['name' => 'Étagères',                  'slug' => 'etageres'],
                    ['name' => 'Enfilades',                 'slug' => 'enfilades'],
                    ['name' => 'Meubles TV',                'slug' => 'meubles-tv'],
                    ['name' => 'Meubles TV muraux',         'slug' => 'meubles-tv-muraux'],
                    ['name' => 'Sièges de bar',             'slug' => 'sieges-de-bar'],
                    ['name' => 'Vaisseliers',               'slug' => 'vaisseliers'],
                    ['name' => 'Vestiaires',                'slug' => 'vestiaires'],
                    ['name' => 'Accessoires pour meubles',  'slug' => 'accessoires-pour-meubles'],
                    ['name' => 'Ensembles de meubles',      'slug' => 'ensembles-de-meubles'],
                    ['name' => 'Meubles de salon',          'slug' => 'meubles-de-salon'],
                    ['name' => 'Meubles de salle à manger', 'slug' => 'meubles-salle-manger'],
                    ['name' => 'Meubles de chambre',        'slug' => 'meubles-chambre'],
                    ['name' => "Meubles d'entrée",          'slug' => 'meubles-entree'],
                    ['name' => 'Meubles bar',               'slug' => 'meubles-bar'],
                    ['name' => 'Mobilier de bureau',        'slug' => 'mobilier-bureau'],
                ],
            ],
            [
                'name' => 'Jardin & Loisirs',
                'slug' => 'jardin-loisirs',
                'children' => [
                    ['name' => 'Mobilier de jardin',                    'slug' => 'mobilier-jardin'],
                    ['name' => 'Mobilier de balcon',                    'slug' => 'mobilier-balcon'],
                    ['name' => 'Mobilier lounge',                       'slug' => 'mobilier-lounge'],
                    ['name' => 'Matériel de camping',                   'slug' => 'materiel-camping'],
                    ['name' => 'Barbecues',                             'slug' => 'barbecues'],
                    ['name' => 'Protections solaires',                  'slug' => 'protections-solaires'],
                    ['name' => 'Piscines',                              'slug' => 'piscines'],
                    ['name' => 'Spa & Bien-être',                       'slug' => 'spa-bien-etre'],
                    ['name' => 'Jeux de plein air',                     'slug' => 'jeux-plein-air'],
                    ['name' => 'Déco jardin',                           'slug' => 'deco-jardin'],
                    ['name' => 'Contenants pour plantes & accessoires', 'slug' => 'contenants-plantes'],
                    ['name' => 'Revêtements sol extérieur',             'slug' => 'revetements-sol-ext'],
                    ['name' => 'Abris de jardin',                       'slug' => 'abris-jardin'],
                    ['name' => 'Équipement jardin',                     'slug' => 'equipement-jardin'],
                    ['name' => 'Brise-vues',                            'slug' => 'brise-vues'],
                    ['name' => 'Arrosage jardin',                       'slug' => 'arrosage-jardin'],
                    ['name' => 'Tapis extérieur',                       'slug' => 'tapis-exterieur'],
                    ['name' => 'Éclairage extérieur',                   'slug' => 'eclairage-exterieur'],
                ],
            ],
            [
                'name' => 'Accessoires',
                'slug' => 'accessoires',
                'children' => [
                    ['name' => 'Miroirs',                   'slug' => 'miroirs'],
                    ['name' => 'Tapis',                     'slug' => 'tapis'],
                    ['name' => 'Coussins',                  'slug' => 'coussins'],
                    ['name' => 'Cadres & photos',           'slug' => 'cadres-photos'],
                    ['name' => 'Vases',                     'slug' => 'vases'],
                    ['name' => 'Bougies & photophores',     'slug' => 'bougies-photophores'],
                    ['name' => 'Horloges',                  'slug' => 'horloges'],
                    ['name' => 'Paniers & boîtes',          'slug' => 'paniers-boites'],
                    ['name' => 'Porte-manteaux',            'slug' => 'porte-manteaux'],
                    ['name' => 'Objets décoratifs',         'slug' => 'objets-decoratifs'],
                    ['name' => 'Rideaux & voilages',        'slug' => 'rideaux-voilages'],
                    ['name' => 'Livres décoratifs',         'slug' => 'livres-decoratifs'],
                    ['name' => 'Tableaux & art mural',      'slug' => 'tableaux-art-mural'],
                    ['name' => 'Sculptures & figurines',    'slug' => 'sculptures-figurines'],
                    ['name' => 'Plantes artificielles',     'slug' => 'plantes-artificielles'],
                ],
            ],
            [
                'name' => 'Luminaires',
                'slug' => 'luminaires',
                'children' => [
                    ['name' => 'Lampes de table',           'slug' => 'lampes-table'],
                    ['name' => 'Lampes de sol',             'slug' => 'lampes-sol'],
                    ['name' => 'Lampes de bureau',          'slug' => 'lampes-bureau'],
                    ['name' => 'Lustres & suspensions',     'slug' => 'lustres-suspensions'],
                    ['name' => 'Appliques murales',         'slug' => 'appliques-murales'],
                    ['name' => 'Spots & rails',             'slug' => 'spots-rails'],
                    ['name' => 'Éclairage extérieur',       'slug' => 'eclairage-ext'],
                    ['name' => 'Guirlandes lumineuses',     'slug' => 'guirlandes-lumineuses'],
                    ['name' => 'Lanternes',                 'slug' => 'lanternes'],
                    ['name' => 'Ampoules & LED',            'slug' => 'ampoules-led'],
                    ['name' => 'Luminaires enfant',         'slug' => 'luminaires-enfant'],
                    ['name' => 'Luminaires salle de bain',  'slug' => 'luminaires-sdb'],
                ],
            ],
            [
                'name' => 'Textiles',
                'slug' => 'textiles',
                'children' => [
                    ['name' => 'Linge de lit',              'slug' => 'linge-de-lit'],
                    ['name' => 'Couvertures & plaids',      'slug' => 'couvertures-plaids'],
                    ['name' => 'Coussins & housses',        'slug' => 'coussins-housses'],
                    ['name' => 'Rideaux & voilages',        'slug' => 'rideaux'],
                    ['name' => 'Tapis',                     'slug' => 'tapis-textiles'],
                    ['name' => 'Serviettes de bain',        'slug' => 'serviettes-bain'],
                    ['name' => 'Linge de table',            'slug' => 'linge-de-table'],
                    ['name' => 'Housses de canapé',         'slug' => 'housses-canape'],
                    ['name' => 'Parures de lit',            'slug' => 'parures-de-lit'],
                    ['name' => 'Oreillers & traversins',    'slug' => 'oreillers-traversins'],
                    ['name' => 'Protège-matelas',           'slug' => 'protege-matelas'],
                    ['name' => 'Tapis de bain',             'slug' => 'tapis-de-bain'],
                ],
            ],
            [
                'name' => 'Enfant',
                'slug' => 'enfant',
                'children' => [
                    ['name' => 'Lits enfant',               'slug' => 'lits-enfant'],
                    ['name' => 'Armoires enfant',           'slug' => 'armoires-enfant'],
                    ['name' => 'Bureaux enfant',            'slug' => 'bureaux-enfant'],
                    ['name' => 'Chaises enfant',            'slug' => 'chaises-enfant'],
                    ['name' => 'Tables enfant',             'slug' => 'tables-enfant'],
                    ['name' => 'Rangement enfant',          'slug' => 'rangement-enfant'],
                    ['name' => 'Déco chambre enfant',       'slug' => 'deco-chambre-enfant'],
                    ['name' => 'Literie enfant',            'slug' => 'literie-enfant'],
                    ['name' => 'Mobilier bébé',             'slug' => 'mobilier-bebe'],
                    ['name' => 'Tapis enfant',              'slug' => 'tapis-enfant'],
                    ['name' => 'Luminaires enfant',         'slug' => 'luminaires-chambre-enfant'],
                    ['name' => 'Textiles enfant',           'slug' => 'textiles-enfant'],
                ],
            ],
            [
                'name' => 'Cuisine',
                'slug' => 'cuisine',
                'children' => [
                    ['name' => 'Tables de cuisine',         'slug' => 'tables-cuisine'],
                    ['name' => 'Chaises de cuisine',        'slug' => 'chaises-cuisine'],
                    ['name' => 'Tabourets de bar',          'slug' => 'tabourets-bar'],
                    ['name' => 'Îlots de cuisine',          'slug' => 'ilots-cuisine'],
                    ['name' => 'Buffets cuisine',           'slug' => 'buffets-cuisine'],
                    ['name' => 'Étagères cuisine',          'slug' => 'etageres-cuisine'],
                    ['name' => 'Rangement cuisine',         'slug' => 'rangement-cuisine'],
                    ['name' => 'Accessoires cuisine',       'slug' => 'accessoires-cuisine'],
                    ['name' => 'Ustensiles de cuisine',     'slug' => 'ustensiles-cuisine'],
                    ['name' => 'Art de la table',           'slug' => 'art-de-la-table'],
                    ['name' => 'Poubelles',                 'slug' => 'poubelles'],
                    ['name' => 'Ensembles repas',           'slug' => 'ensembles-repas'],
                ],
            ],
            [
                'name' => 'Salle de bain',
                'slug' => 'salle-de-bain',
                'children' => [
                    ['name' => 'Meubles de salle de bain',  'slug' => 'meubles-sdb'],
                    ['name' => 'Miroirs salle de bain',     'slug' => 'miroirs-sdb'],
                    ['name' => 'Accessoires salle de bain', 'slug' => 'accessoires-sdb'],
                    ['name' => 'Rangement salle de bain',   'slug' => 'rangement-sdb'],
                    ['name' => 'Porte-serviettes',          'slug' => 'porte-serviettes'],
                    ['name' => 'Panier à linge',            'slug' => 'panier-linge'],
                    ['name' => 'Tapis de bain',             'slug' => 'tapis-bain'],
                    ['name' => 'Éclairage salle de bain',   'slug' => 'eclairage-sdb'],
                    ['name' => 'Distributeurs & pompes',    'slug' => 'distributeurs-pompes'],
                    ['name' => 'Bacs & douches',            'slug' => 'bacs-douches'],
                ],
            ],
            [
                'name' => 'Animalerie',
                'slug' => 'animalerie',
                'children' => [
                    ['name' => 'Chiens',                    'slug' => 'chiens'],
                    ['name' => 'Chats',                     'slug' => 'chats'],
                    ['name' => 'Petits animaux',            'slug' => 'petits-animaux'],
                    ['name' => 'Oiseaux',                   'slug' => 'oiseaux'],
                    ['name' => 'Poissons',                  'slug' => 'poissons'],
                    ['name' => 'Couchages animaux',         'slug' => 'couchages-animaux'],
                    ['name' => 'Gamelles & distributeurs',  'slug' => 'gamelles-distributeurs'],
                    ['name' => 'Jouets animaux',            'slug' => 'jouets-animaux'],
                    ['name' => 'Transport & promenade',     'slug' => 'transport-promenade'],
                    ['name' => 'Accessoires animaux',       'slug' => 'accessoires-animaux'],
                ],
            ],
            [
                'name' => 'Bricolage',
                'slug' => 'bricolage',
                'children' => [
                    ['name' => 'Outils électroportatifs',   'slug' => 'outils-electroportatifs'],
                    ['name' => 'Outillage à main',          'slug' => 'outillage-main'],
                    ['name' => 'Visserie & quincaillerie',  'slug' => 'visserie-quincaillerie'],
                    ['name' => 'Peinture & enduit',         'slug' => 'peinture-enduit'],
                    ['name' => 'Revêtements sol',           'slug' => 'revetements-sol'],
                    ['name' => 'Revêtements mur',           'slug' => 'revetements-mur'],
                    ['name' => 'Électricité',               'slug' => 'electricite'],
                    ['name' => 'Plomberie',                 'slug' => 'plomberie'],
                    ['name' => 'Rangement atelier',         'slug' => 'rangement-atelier'],
                    ['name' => 'Sécurité maison',           'slug' => 'securite-maison'],
                ],
            ],
            [
                'name' => 'Électro & Entretien',
                'slug' => 'electro-entretien',
                'children' => [
                    ['name' => 'Aspirateurs',               'slug' => 'aspirateurs'],
                    ['name' => 'Robots ménagers',           'slug' => 'robots-menagers'],
                    ['name' => 'Machines à café',           'slug' => 'machines-cafe'],
                    ['name' => 'Lave-linge',                'slug' => 'lave-linge'],
                    ['name' => 'Sèche-linge',               'slug' => 'seche-linge'],
                    ['name' => 'Réfrigérateurs',            'slug' => 'refrigerateurs'],
                    ['name' => 'Congélateurs',              'slug' => 'congelateurs'],
                    ['name' => 'Fours & micro-ondes',       'slug' => 'fours-micro-ondes'],
                    ['name' => 'Lave-vaisselle',            'slug' => 'lave-vaisselle'],
                    ['name' => 'Hottes',                    'slug' => 'hottes'],
                    ['name' => 'Fer à repasser',            'slug' => 'fer-repasser'],
                    ['name' => 'Nettoyeurs vapeur',         'slug' => 'nettoyeurs-vapeur'],
                ],
            ],
        ];

        foreach ($categories as $position => $data) {
            $category = Category::create([
                'name'      => $data['name'],
                'slug'      => $data['slug'],
                'icon'      => null,
                'position'  => $position,
                'is_active' => true,
            ]);

            foreach ($data['children'] as $childPosition => $child) {
                Subcategory::create([
                    'category_id' => $category->id,
                    'name'        => $child['name'],
                    'slug'        => $child['slug'],
                    'position'    => $childPosition,
                    'is_active'   => true,
                ]);
            }
        }
    }
}