<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('GroupTableSeeder');
		$this->command->info('Group table seeded!');

		$this->call('CampusMetaTableSeeder');
		$this->command->info('Campus meta table seeded!');

		$this->call('FashionBrandsTableSeeder');
		$this->command->info('Fashion brands table seeded!');

		$this->call('StreetMetaTableSeeder');
		$this->command->info('Street meta table seeded!');
	}

}

class GroupTableSeeder extends Seeder {

	public function run() {
		DB::table('groups')->delete();
		DB::table('groups')->truncate();

		Group::create(array('name'=>'Active Photographers'));
		Group::create(array('name'=>'Retired Photographers'));
		Group::create(array('name'=>'Bloggers'));
		Group::create(array('name'=>'Staff'));
		Group::create(array('name'=>'Managers'));
		Group::create(array('name'=>'Super Users'));
		Group::create(array('name'=>'Blacklist'));
	}
	
}

class CampusMetaTableSeeder extends Seeder {

	public function run() {
		DB::table('campus_meta')->delete();
		DB::table('campus_meta')->truncate();
		
		CampusMeta::create(array('name_ko'=>'가천대','slug'=>'gachon'));
		CampusMeta::create(array('name_ko'=>'가톨릭대','slug'=>'catholic'));
		CampusMeta::create(array('name_ko'=>'강원대','slug'=>'kangwon'));
		CampusMeta::create(array('name_ko'=>'건국대','slug'=>'konkuk'));
		CampusMeta::create(array('name_ko'=>'건국대(글로컬)','slug'=>'glocal'));
		CampusMeta::create(array('name_ko'=>'경기대','slug'=>'kgu'));
		CampusMeta::create(array('name_ko'=>'경북대','slug'=>'knu'));
		CampusMeta::create(array('name_ko'=>'경상대','slug'=>'gsnu'));
		CampusMeta::create(array('name_ko'=>'경성대','slug'=>'ks'));
		CampusMeta::create(array('name_ko'=>'경인교대','slug'=>'ginue'));
		CampusMeta::create(array('name_ko'=>'경찰대학','slug'=>'police'));
		CampusMeta::create(array('name_ko'=>'경희대','slug'=>'khu'));
		CampusMeta::create(array('name_ko'=>'계명대','slug'=>'kmu'));
		CampusMeta::create(array('name_ko'=>'계원예대','slug'=>'kaywon'));
		CampusMeta::create(array('name_ko'=>'고려대','slug'=>'korea'));
		CampusMeta::create(array('name_ko'=>'고려대(세종)','slug'=>'korea-sejong'));
		CampusMeta::create(array('name_ko'=>'공군사관학교','slug'=>'afa'));
		CampusMeta::create(array('name_ko'=>'공주대','slug'=>'kongju'));
		CampusMeta::create(array('name_ko'=>'광운대','slug'=>'kw'));
		CampusMeta::create(array('name_ko'=>'국민대','slug'=>'kookmin'));
		CampusMeta::create(array('name_ko'=>'국제패션학교','slug'=>'kf'));
		CampusMeta::create(array('name_ko'=>'노인대학','slug'=>'senior'));
		CampusMeta::create(array('name_ko'=>'단국대','slug'=>'dku'));
		CampusMeta::create(array('name_ko'=>'단국대(천안)','slug'=>'dku-cheonan'));
		CampusMeta::create(array('name_ko'=>'대구대','slug'=>'daegu'));
		CampusMeta::create(array('name_ko'=>'대진대','slug'=>'daejin'));
		CampusMeta::create(array('name_ko'=>'덕성여대','slug'=>'duksung'));
		CampusMeta::create(array('name_ko'=>'동국대','slug'=>'dongguk'));
		CampusMeta::create(array('name_ko'=>'동국대(경주)','slug'=>'dongguk-gyeongju'));
		CampusMeta::create(array('name_ko'=>'동덕여대','slug'=>'dongduk'));
		CampusMeta::create(array('name_ko'=>'동명대','slug'=>'tu'));
		CampusMeta::create(array('name_ko'=>'동아대','slug'=>'donga'));
		CampusMeta::create(array('name_ko'=>'동양미래대','slug'=>'dongyang'));
		CampusMeta::create(array('name_ko'=>'라사라패션학교','slug'=>'rasara'));
		CampusMeta::create(array('name_ko'=>'명지대','slug'=>'mju'));
		CampusMeta::create(array('name_ko'=>'목원대','slug'=>'mokwon'));
		CampusMeta::create(array('name_ko'=>'백석대','slug'=>'bu'));
		CampusMeta::create(array('name_ko'=>'부경대','slug'=>'pknu'));
		CampusMeta::create(array('name_ko'=>'부산경상대','slug'=>'bsks'));
		CampusMeta::create(array('name_ko'=>'부산대','slug'=>'pusan'));
		CampusMeta::create(array('name_ko'=>'삼육대','slug'=>'syu'));
		CampusMeta::create(array('name_ko'=>'상명대','slug'=>'smu'));
		CampusMeta::create(array('name_ko'=>'상명대(천안)','slug'=>'smu-cheonan'));
		CampusMeta::create(array('name_ko'=>'상지대','slug'=>'sangji'));
		CampusMeta::create(array('name_ko'=>'서강대','slug'=>'sogang'));
		CampusMeta::create(array('name_ko'=>'서경대','slug'=>'skuniv'));
		CampusMeta::create(array('name_ko'=>'서울과기대','slug'=>'seoultech'));
		CampusMeta::create(array('name_ko'=>'서울대','slug'=>'snu'));
		CampusMeta::create(array('name_ko'=>'서울모드패션학교','slug'=>'seoulmode'));
		CampusMeta::create(array('name_ko'=>'서울시립대','slug'=>'uos'));
		CampusMeta::create(array('name_ko'=>'서울여대','slug'=>'swu'));
		CampusMeta::create(array('name_ko'=>'서울예전','slug'=>'seoularts'));
		CampusMeta::create(array('name_ko'=>'서울종합예술학교','slug'=>'sac'));
		CampusMeta::create(array('name_ko'=>'성균관대','slug'=>'skku'));
		CampusMeta::create(array('name_ko'=>'성신여대','slug'=>'sungshin'));
		CampusMeta::create(array('name_ko'=>'세종대','slug'=>'sejong'));
		CampusMeta::create(array('name_ko'=>'수원대','slug'=>'suwon'));
		CampusMeta::create(array('name_ko'=>'숙명여대','slug'=>'sookmyung'));
		CampusMeta::create(array('name_ko'=>'순천향대','slug'=>'sch'));
		CampusMeta::create(array('name_ko'=>'숭실대','slug'=>'ssu'));
		CampusMeta::create(array('name_ko'=>'숭의여대','slug'=>'sewc'));
		CampusMeta::create(array('name_ko'=>'신구대','slug'=>'shingu'));
		CampusMeta::create(array('name_ko'=>'아주대','slug'=>'ajou'));
		CampusMeta::create(array('name_ko'=>'에스모드패션학교','slug'=>'esmod'));
		CampusMeta::create(array('name_ko'=>'연세대','slug'=>'yonsei'));
		CampusMeta::create(array('name_ko'=>'연세대(원주)','slug'=>'yonsei-wonju'));
		CampusMeta::create(array('name_ko'=>'영남대','slug'=>'yu'));
		CampusMeta::create(array('name_ko'=>'용인대','slug'=>'yongin'));
		CampusMeta::create(array('name_ko'=>'원광대','slug'=>'wku'));
		CampusMeta::create(array('name_ko'=>'육군사관학교','slug'=>'kma'));
		CampusMeta::create(array('name_ko'=>'을지대','slug'=>'eulji'));
		CampusMeta::create(array('name_ko'=>'이화여대','slug'=>'ewha'));
		CampusMeta::create(array('name_ko'=>'인천대','slug'=>'incheon'));
		CampusMeta::create(array('name_ko'=>'인하공업전문대학','slug'=>'itc'));
		CampusMeta::create(array('name_ko'=>'인하대','slug'=>'inha'));
		CampusMeta::create(array('name_ko'=>'전남대','slug'=>'chonnam'));
		CampusMeta::create(array('name_ko'=>'전북대','slug'=>'chonbuk'));
		CampusMeta::create(array('name_ko'=>'전주대','slug'=>'jj'));
		CampusMeta::create(array('name_ko'=>'조선대','slug'=>'chosun'));
		CampusMeta::create(array('name_ko'=>'중앙대','slug'=>'cau'));
		CampusMeta::create(array('name_ko'=>'중앙대(안성)','slug'=>'cau-ansung'));
		CampusMeta::create(array('name_ko'=>'창원대','slug'=>'changwon'));
		CampusMeta::create(array('name_ko'=>'청주대','slug'=>'cju'));
		CampusMeta::create(array('name_ko'=>'충남대','slug'=>'cnu'));
		CampusMeta::create(array('name_ko'=>'충북대','slug'=>'chungbuk'));
		CampusMeta::create(array('name_ko'=>'카이스트','slug'=>'kaist'));
		CampusMeta::create(array('name_ko'=>'포항공대','slug'=>'postech'));
		CampusMeta::create(array('name_ko'=>'한경대','slug'=>'hknu'));
		CampusMeta::create(array('name_ko'=>'한국산기대','slug'=>'kpu'));
		CampusMeta::create(array('name_ko'=>'한국외대','slug'=>'hufs'));
		CampusMeta::create(array('name_ko'=>'한국외대(글로벌)','slug'=>'hufs-global'));
		CampusMeta::create(array('name_ko'=>'한국체대','slug'=>'knsu'));
		CampusMeta::create(array('name_ko'=>'한국항공대','slug'=>'hangkong'));
		CampusMeta::create(array('name_ko'=>'한국해양대','slug'=>'kmou'));
		CampusMeta::create(array('name_ko'=>'한남대','slug'=>'hannam'));
		CampusMeta::create(array('name_ko'=>'한림대','slug'=>'hallym'));
		CampusMeta::create(array('name_ko'=>'한밭대','slug'=>'hanbat'));
		CampusMeta::create(array('name_ko'=>'한서대','slug'=>'hanseo'));
		CampusMeta::create(array('name_ko'=>'한성대','slug'=>'hansung'));
		CampusMeta::create(array('name_ko'=>'한신대','slug'=>'hs'));
		CampusMeta::create(array('name_ko'=>'한양대','slug'=>'hanyang'));
		CampusMeta::create(array('name_ko'=>'한양대(ERICA)','slug'=>'hanyang-erica'));
		CampusMeta::create(array('name_ko'=>'한예종','slug'=>'karts'));
		CampusMeta::create(array('name_ko'=>'해군사관학교','slug'=>'navy'));
		CampusMeta::create(array('name_ko'=>'호서대','slug'=>'hoseo'));
		CampusMeta::create(array('name_ko'=>'홍익대','slug'=>'hongik'));
		CampusMeta::create(array('name_ko'=>'홍익대(세종)','slug'=>'hongik-sejong'));
		CampusMeta::create(array('name_en'=>'Beppu Univ.','slug'=>'beppu'));
		CampusMeta::create(array('name_en'=>'Dublin City Univ.','slug'=>'dublin-city'));
		CampusMeta::create(array('name_en'=>'Jonkoping Univ.','slug'=>'jonkoping'));
		CampusMeta::create(array('name_en'=>'London College of Fashion','slug'=>'london-fashion'));
		CampusMeta::create(array('name_en'=>'Monash Univ.','slug'=>'monash'));
		CampusMeta::create(array('name_en'=>'Nihon Univ.','slug'=>'nihon'));
		CampusMeta::create(array('name_en'=>'SCAD','slug'=>'scad'));
		CampusMeta::create(array('name_en'=>'UCLA','slug'=>'ucla'));
		CampusMeta::create(array('name_en'=>'UNIST','slug'=>'unist'));
		CampusMeta::create(array('name_en'=>'Univ of Iowa','slug'=>'iowa'));
		CampusMeta::create(array('name_en'=>'Univ of Melbourne','slug'=>'melbourne'));
		CampusMeta::create(array('name_en'=>'Univ of Toronto','slug'=>'toronto'));
		CampusMeta::create(array('name_en'=>'Yokohama National Univ.','slug'=>'yokohama-national'));
		CampusMeta::create(array('name_en'=>'York Univ.','slug'=>'york'));
	}
	
}

class FashionBrandsTableSeeder extends Seeder {

	public function run() {
		DB::table('fashion_brands')->delete();
		DB::table('fashion_brands')->truncate();

		FashionBrand::create(array('name_en'=>'8-SECONDS','slug'=>'8-seconds'));
		FashionBrand::create(array('name_en'=>'ACCESSORIZE','slug'=>'accessorize'));
		FashionBrand::create(array('name_en'=>'ACNE','slug'=>'acne'));
		FashionBrand::create(array('name_en'=>'ALO','slug'=>'alo'));
		FashionBrand::create(array('name_en'=>'A-LAND','slug'=>'a-land'));
		FashionBrand::create(array('name_en'=>'A.P.C','slug'=>'apc'));
		FashionBrand::create(array('name_en'=>'ABERCROMBIE','slug'=>'abercrombie'));
		FashionBrand::create(array('name_en'=>'ADIDAS','slug'=>'adidas'));
		FashionBrand::create(array('name_en'=>'ALDO','slug'=>'aldo'));
		FashionBrand::create(array('name_en'=>'ALEXANDER MCQUEEN','slug'=>'alexander-mcqueen'));
		FashionBrand::create(array('name_en'=>'ALEXANDER WANG','slug'=>'alexander-wang'));
		FashionBrand::create(array('name_en'=>'AMERICAN APPAREL','slug'=>'american-apparel'));
		FashionBrand::create(array('name_en'=>'ANNE KLEIN','slug'=>'anne-klein'));
		FashionBrand::create(array('name_en'=>'APRIL 77','slug'=>'april-77'));
		FashionBrand::create(array('name_en'=>'ARMANI','slug'=>'armani'));
		FashionBrand::create(array('name_en'=>'ARMANI EXCHANGE','slug'=>'armani-exchange'));
		FashionBrand::create(array('name_en'=>'ASICS','slug'=>'asics'));
		FashionBrand::create(array('name_en'=>'ASOS','slug'=>'asos'));
		FashionBrand::create(array('name_en'=>'BALENCIAGA','slug'=>'balenciaga'));
		FashionBrand::create(array('name_en'=>'BALLY','slug'=>'bally'));
		FashionBrand::create(array('name_en'=>'BALMAIN','slug'=>'balmain'));
		FashionBrand::create(array('name_en'=>'BANANA REPUBLIC','slug'=>'banana-republic'));
		FashionBrand::create(array('name_en'=>'BEAN POLE','slug'=>'bean-pole'));
		FashionBrand::create(array('name_en'=>'BENETTON','slug'=>'benetton'));
		FashionBrand::create(array('name_en'=>'BEYOND CLOSET','slug'=>'beyond-closet'));
		FashionBrand::create(array('name_en'=>'BIKE REPAIR SHOP','slug'=>'bike-repair-shop'));
		FashionBrand::create(array('name_en'=>'BIRKEN STOCK','slug'=>'birken-stock'));
		FashionBrand::create(array('name_en'=>'BON','slug'=>'bon'));
		FashionBrand::create(array('name_en'=>'BROOKS BROTHERS','slug'=>'brooks-brothers'));
		FashionBrand::create(array('name_en'=>'BROWNBREATH','slug'=>'brownbreath'));
		FashionBrand::create(array('name_en'=>'BURBERRY','slug'=>'burberry'));
		FashionBrand::create(array('name_en'=>'BVLGARI','slug'=>'bvlgari'));
		FashionBrand::create(array('name_en'=>'CALVIN KLEIN','slug'=>'calvin-klein'));
		FashionBrand::create(array('name_en'=>'CAMPER','slug'=>'camper'));
		FashionBrand::create(array('name_en'=>'CAMPO MARZIO','slug'=>'campo-marzio'));
		FashionBrand::create(array('name_en'=>'CARHARTT','slug'=>'carhartt'));
		FashionBrand::create(array('name_en'=>'CASIO','slug'=>'casio'));
		FashionBrand::create(array('name_en'=>'CELINE','slug'=>'celine'));
		FashionBrand::create(array('name_en'=>'CHANEL','slug'=>'chanel'));
		FashionBrand::create(array('name_en'=>'CHEAP MONDAY','slug'=>'cheap-monday'));
		FashionBrand::create(array('name_en'=>'CHLOE','slug'=>'chloe'));
		FashionBrand::create(array('name_en'=>'CHRISTIAN DIOR','slug'=>'christian-dior'));
		FashionBrand::create(array('name_en'=>'CLARKS','slug'=>'clarks'));
		FashionBrand::create(array('name_en'=>'CLUB MONACO','slug'=>'club-monaco'));
		FashionBrand::create(array('name_en'=>'COACH','slug'=>'coach'));
		FashionBrand::create(array('name_en'=>'CODES COMBINE','slug'=>'codes-combine'));
		FashionBrand::create(array('name_en'=>'COMING STEP','slug'=>'coming-step'));
		FashionBrand::create(array('name_en'=>'COMME DES GARÇONS','slug'=>'comme-des-garcons'));
		FashionBrand::create(array('name_en'=>'CONVERSE','slug'=>'converse'));
		FashionBrand::create(array('name_en'=>'COURONNE','slug'=>'couronne'));
		FashionBrand::create(array('name_en'=>'COVERNAT','slug'=>'covernat'));
		FashionBrand::create(array('name_en'=>'CUSTOMELLOW','slug'=>'customellow'));
		FashionBrand::create(array('name_en'=>'D&G','slug'=>'dolce-gabbana'));
		FashionBrand::create(array('name_en'=>'DAKS','slug'=>'daks'));
		FashionBrand::create(array('name_en'=>'DIESEL','slug'=>'diesel'));
		FashionBrand::create(array('name_en'=>'DKNY','slug'=>'dkny'));
		FashionBrand::create(array('name_en'=>'DR MARTENS','slug'=>'dr-martens'));
		FashionBrand::create(array('name_en'=>'DSQUARED2','slug'=>'dsquared2'));
		FashionBrand::create(array('name_en'=>'EAST PAK','slug'=>'east-pak'));
		FashionBrand::create(array('name_en'=>'FENDI','slug'=>'fendi'));
		FashionBrand::create(array('name_en'=>'FREITAG','slug'=>'freitag'));
		FashionBrand::create(array('name_en'=>'FERRAGAMO','slug'=>'ferragamo'));
		FashionBrand::create(array('name_en'=>'FILA','slug'=>'fila'));
		FashionBrand::create(array('name_en'=>'FOREVER21','slug'=>'forever21'));
		FashionBrand::create(array('name_en'=>'FRED PERRY','slug'=>'fred-perry'));
		FashionBrand::create(array('name_en'=>'GALAXY NOTE 3+ GEAR','slug'=>'galaxy-note-3-gear'));
		FashionBrand::create(array('name_en'=>'GAP','slug'=>'gap'));
		FashionBrand::create(array('name_en'=>'GIORDANO','slug'=>'giordano'));
		FashionBrand::create(array('name_en'=>'GENERAL IDEA','slug'=>'general-idea'));
		FashionBrand::create(array('name_en'=>'GENTLE MONSTER','slug'=>'gentle-monster'));
		FashionBrand::create(array('name_en'=>'GIORGIO ARMANI','slug'=>'giorgio-armani'));
		FashionBrand::create(array('name_en'=>'GIVENCHY','slug'=>'givenchy'));
		FashionBrand::create(array('name_en'=>'GUCCI','slug'=>'gucci'));
		FashionBrand::create(array('name_en'=>'GUESS','slug'=>'guess'));
		FashionBrand::create(array('name_en'=>'H&M','slug'=>'hm'));
		FashionBrand::create(array('name_en'=>'HAZZYS','slug'=>'hazzys'));
		FashionBrand::create(array('name_en'=>'HEAD','slug'=>'head'));
		FashionBrand::create(array('name_en'=>'HAWKINS','slug'=>'hawkins'));
		FashionBrand::create(array('name_en'=>'HOLLISTER','slug'=>'hollister'));
		FashionBrand::create(array('name_en'=>'HUNTER','slug'=>'hunter'));
		FashionBrand::create(array('name_en'=>'INCASE','slug'=>'incase'));
		FashionBrand::create(array('name_en'=>'IT MICHAA','slug'=>'it-michaa'));
		FashionBrand::create(array('name_en'=>'J.CREW','slug'=>'j-crew'));
		FashionBrand::create(array('name_en'=>'J.ESTINA','slug'=>'j-estina'));
		FashionBrand::create(array('name_en'=>'JANSPORT','slug'=>'jansport'));
		FashionBrand::create(array('name_en'=>'JIL SANDER','slug'=>'jil-sander'));
		FashionBrand::create(array('name_en'=>'JILL STUART','slug'=>'jill-stuart'));
		FashionBrand::create(array('name_en'=>'JILL BY JILL STUART','slug'=>'jill-by-jill-stuart'));
		FashionBrand::create(array('name_en'=>'JUUN.J','slug'=>'juun-j'));
		FashionBrand::create(array('name_en'=>'KAI-AKKMAN','slug'=>'kai-akkman'));
		FashionBrand::create(array('name_en'=>'KANGOL','slug'=>'kangol'));
		FashionBrand::create(array('name_en'=>'KEDS','slug'=>'keds'));
		FashionBrand::create(array('name_en'=>'KENZO','slug'=>'kenzo'));
		FashionBrand::create(array('name_en'=>'KIPLING','slug'=>'kipling'));
		FashionBrand::create(array('name_en'=>'KRIS VAN ASSCHE','slug'=>'kris-van-assche'));
		FashionBrand::create(array('name_en'=>'LACOSTE','slug'=>'lacoste'));
		FashionBrand::create(array('name_en'=>'LACOSTE L!VE','slug'=>'lacoste-live'));
		FashionBrand::create(array('name_en'=>'LANVIN','slug'=>'lanvin'));
		FashionBrand::create(array('name_en'=>'LAP','slug'=>'lap'));
		FashionBrand::create(array('name_en'=>'LAPALETTE','slug'=>'lapalette'));
		FashionBrand::create(array('name_en'=>'LESHOP','slug'=>'leshop'));
		FashionBrand::create(array('name_en'=>'LESPORTSAC','slug'=>'lesportsac'));
		FashionBrand::create(array('name_en'=>"LEVI'S",'slug'=>'levis'));
		FashionBrand::create(array('name_en'=>'LEWITT','slug'=>'lewitt'));
		FashionBrand::create(array('name_en'=>'LIFUL','slug'=>'liful'));
		FashionBrand::create(array('name_en'=>'LONGCHAMP','slug'=>'longchamp'));
		FashionBrand::create(array('name_en'=>'LOUIS QUATORZE','slug'=>'louis-quatorze'));
		FashionBrand::create(array('name_en'=>'LOUIS VUITTON','slug'=>'louis-vuitton'));
		FashionBrand::create(array('name_en'=>'LOW CLASSIC','slug'=>'low-classic'));
		FashionBrand::create(array('name_en'=>'LOVCAT','slug'=>'lovcat'));
		FashionBrand::create(array('name_en'=>'LUCKY CHOUETTE','slug'=>'lucky-chouette'));
		FashionBrand::create(array('name_en'=>'MANDARINA DUCK','slug'=>'mandarina-duck'));
		FashionBrand::create(array('name_en'=>'MANGO','slug'=>'mango'));
		FashionBrand::create(array('name_en'=>'MARC JACOBS','slug'=>'mark-jacobs'));
		FashionBrand::create(array('name_en'=>'MARC BY MARC JACOBS','slug'=>'mark-by-mark-jacobs'));
		FashionBrand::create(array('name_en'=>'MARGARIN FINGERS','slug'=>'margarin-fingers'));
		FashionBrand::create(array('name_en'=>'MCM','slug'=>'mcm'));
		FashionBrand::create(array('name_en'=>'MELLOW PLANET','slug'=>'mellow-planet'));
		FashionBrand::create(array('name_en'=>'METROCITY','slug'=>'metrocity'));
		FashionBrand::create(array('name_en'=>'MICHAEL KORS','slug'=>'michael-kors'));
		FashionBrand::create(array('name_en'=>'MINNETONKA','slug'=>'minnetonka'));
		FashionBrand::create(array('name_en'=>'MIXXO','slug'=>'mixxo'));
		FashionBrand::create(array('name_en'=>'MLB','slug'=>'mlb'));
		FashionBrand::create(array('name_en'=>'MUJI','slug'=>'muji'));
		FashionBrand::create(array('name_en'=>'MULBERRY','slug'=>'mulberry'));
		FashionBrand::create(array('name_en'=>'MVIO','slug'=>'mvio'));
		FashionBrand::create(array('name_en'=>'NEW BALANCE','slug'=>'new-balance'));
		FashionBrand::create(array('name_en'=>'NIKE','slug'=>'nike'));
		FashionBrand::create(array('name_en'=>'NINE WEST','slug'=>'nine-west'));
		FashionBrand::create(array('name_en'=>'NUDIE JEANS','slug'=>'nudie-jeans'));
		FashionBrand::create(array('name_en'=>'OAKLEY','slug'=>'oakley'));
		FashionBrand::create(array('name_en'=>'OBEY','slug'=>'obey'));
		FashionBrand::create(array('name_en'=>'OILILY','slug'=>'oilily'));
		FashionBrand::create(array('name_en'=>'OLIVE DES OLIVE','slug'=>'olive-des-olive'));
		FashionBrand::create(array('name_en'=>'OMEGA','slug'=>'omega'));
		FashionBrand::create(array('name_en'=>'OST','slug'=>'ost'));
		FashionBrand::create(array('name_en'=>'OZOC','slug'=>'ozoc'));
		FashionBrand::create(array('name_en'=>'PAUL SMITH','slug'=>'paul-smith'));
		FashionBrand::create(array('name_en'=>'PLAC JEANS','slug'=>'plac-jeans'));
		FashionBrand::create(array('name_en'=>'PLASTIC ISLAND','slug'=>'plastic-island'));
		FashionBrand::create(array('name_en'=>'POLO','slug'=>'polo'));
		FashionBrand::create(array('name_en'=>'PONY','slug'=>'pony'));
		FashionBrand::create(array('name_en'=>'PRADA','slug'=>'prada'));
		FashionBrand::create(array('name_en'=>'PUMA','slug'=>'puma'));
		FashionBrand::create(array('name_en'=>'QUA','slug'=>'qua'));
		FashionBrand::create(array('name_en'=>'RAF SIMONS','slug'=>'raf-simons'));
		FashionBrand::create(array('name_en'=>'RALPH LAUREN','slug'=>'ralph-lauren'));
		FashionBrand::create(array('name_en'=>'RAY BAN','slug'=>'ray-ban'));
		FashionBrand::create(array('name_en'=>'REEBOK','slug'=>'reebok'));
		FashionBrand::create(array('name_en'=>'RICK OWENS','slug'=>'rick-owens'));
		FashionBrand::create(array('name_en'=>'ROCK PORT','slug'=>'rock-port'));
		FashionBrand::create(array('name_en'=>'ROEM','slug'=>'roem'));
		FashionBrand::create(array('name_en'=>'ROMANTIC MOVE','slug'=>'romantic-move'));
		FashionBrand::create(array('name_en'=>'SAINT JAMES','slug'=>'saint-james'));
		FashionBrand::create(array('name_en'=>'SAMSONITE','slug'=>'samsonite'));
		FashionBrand::create(array('name_en'=>'SAUCONY','slug'=>'saucony'));
		FashionBrand::create(array('name_en'=>'SEIKO','slug'=>'seiko'));
		FashionBrand::create(array('name_en'=>'SERIES;','slug'=>'series'));
		FashionBrand::create(array('name_en'=>'SISLEY','slug'=>'sisley'));
		FashionBrand::create(array('name_en'=>'SODA','slug'=>'soda'));
		FashionBrand::create(array('name_en'=>'SOUP','slug'=>'soup'));
		FashionBrand::create(array('name_en'=>'SPAO','slug'=>'spao'));
		FashionBrand::create(array('name_en'=>'SPRIS','slug'=>'spris'));
		FashionBrand::create(array('name_en'=>'STEVEJ & YONIP','slug'=>'stevej-yonip'));
		FashionBrand::create(array('name_en'=>'STYLE NANDA','slug'=>'style-nanda'));
		FashionBrand::create(array('name_en'=>'SUE COMMA BONNIE','slug'=>'sue-comma-bonnie'));
		FashionBrand::create(array('name_en'=>'SUPER','slug'=>'super'));
		FashionBrand::create(array('name_en'=>'SUPERGA','slug'=>'superga'));
		FashionBrand::create(array('name_en'=>'SWAROVSKI','slug'=>'swarovski'));
		FashionBrand::create(array('name_en'=>'SWATCH','slug'=>'swatch'));
		FashionBrand::create(array('name_en'=>'SYSTEM','slug'=>'system'));
		FashionBrand::create(array('name_en'=>'T.I FOR MEN','slug'=>'ti-for-men'));
		FashionBrand::create(array('name_en'=>'TAG HEUER','slug'=>'tag-heuer'));
		FashionBrand::create(array('name_en'=>'TANDY','slug'=>'tandy'));
		FashionBrand::create(array('name_en'=>'TATE','slug'=>'tate'));
		FashionBrand::create(array('name_en'=>'THE NORTH FACE','slug'=>'the-north-face'));
		FashionBrand::create(array('name_en'=>'THEORY','slug'=>'theory'));
		FashionBrand::create(array('name_en'=>'THURSDAY ISLAND','slug'=>'thursday-island'));
		FashionBrand::create(array('name_en'=>'TIMBERLAND','slug'=>'timberland'));
		FashionBrand::create(array('name_en'=>'TIME','slug'=>'time'));
		FashionBrand::create(array('name_en'=>'TIMEX','slug'=>'timex'));
		FashionBrand::create(array('name_en'=>'TISSOT','slug'=>'tissot'));
		FashionBrand::create(array('name_en'=>'T-LEVEL','slug'=>'t-level'));
		FashionBrand::create(array('name_en'=>'TNGT','slug'=>'tngt'));
		FashionBrand::create(array('name_en'=>'TOMBOY','slug'=>'tomboy'));
		FashionBrand::create(array('name_en'=>'TOMFORD','slug'=>'tomford'));
		FashionBrand::create(array('name_en'=>'TOMMY HILFIGER','slug'=>'tommy-hilfiger'));
		FashionBrand::create(array('name_en'=>'TOMS','slug'=>'toms'));
		FashionBrand::create(array('name_en'=>'TOPMAN','slug'=>'topman'));
		FashionBrand::create(array('name_en'=>'TOPSHOP','slug'=>'topshop'));
		FashionBrand::create(array('name_en'=>'TOP10','slug'=>'top10'));
		FashionBrand::create(array('name_en'=>'TORY BURCH','slug'=>'tory-burch'));
		FashionBrand::create(array('name_en'=>'UGG','slug'=>'ugg'));
		FashionBrand::create(array('name_en'=>'UNIQLO','slug'=>'uniqlo'));
		FashionBrand::create(array('name_en'=>'URBAN OUTFITTERS','slug'=>'urban-outfitters'));
		FashionBrand::create(array('name_en'=>'VANS','slug'=>'vans'));
		FashionBrand::create(array('name_en'=>'VERSACE','slug'=>'versace'));
		FashionBrand::create(array('name_en'=>'VIVA STUDIO','slug'=>'viva-studio'));
		FashionBrand::create(array('name_en'=>'VIVIENNE WESTWOOD','slug'=>'vivienne-westwood'));
		FashionBrand::create(array('name_en'=>'YOHJI YAMAMOTO','slug'=>'yohji-yamamoto'));
		FashionBrand::create(array('name_en'=>'YSL','slug'=>'ysl'));
		FashionBrand::create(array('name_en'=>'ZARA','slug'=>'zara'));
		FashionBrand::create(array('name_en'=>'LOCLE','slug'=>'locle'));
		FashionBrand::create(array('name_en'=>'GRAFIK PLASTIC','slug'=>'grafik-plastic'));
		FashionBrand::create(array('name_en'=>'VINTAGE HOLLYWOOD','slug'=>'vintage-hollywood'));
		FashionBrand::create(array('name_en'=>'87MM','slug'=>'87mm'));
		FashionBrand::create(array('name_en'=>'NOHANT','slug'=>'nohant'));
		FashionBrand::create(array('name_en'=>'S=YZ','slug'=>'s-yz'));
		FashionBrand::create(array('name_en'=>'UNBOUNDED AWE','slug'=>'unbounded-awe'));
		FashionBrand::create(array('name_en'=>'BPB','slug'=>'bpb'));
		FashionBrand::create(array('name_en'=>'VANDALIST','slug'=>'vandalist'));
		FashionBrand::create(array('name_en'=>'EXCLUSIVE','slug'=>'exclusive'));
		FashionBrand::create(array('name_en'=>'ROCKET X LUNCH','slug'=>'rocketxlunch'));
		FashionBrand::create(array('name_en'=>'BLINDNESS','slug'=>'blindness'));
		FashionBrand::create(array('name_en'=>'FUNFROMFUN','slug'=>'funfromfun'));
		FashionBrand::create(array('name_en'=>'COMPATHY','slug'=>'compathy'));
		FashionBrand::create(array('name_en'=>'ZERO SECOND','slug'=>'zero-second'));
		FashionBrand::create(array('name_en'=>'AFM','slug'=>'afm'));
		FashionBrand::create(array('name_en'=>'COUREGIEM','slug'=>'couregiem'));
		FashionBrand::create(array('name_en'=>"CHARM'S",'slug'=>'charms'));
		FashionBrand::create(array('name_en'=>'OiOi','slug'=>'oioi'));
		FashionBrand::create(array('name_en'=>'SALADBOWLS','slug'=>'saladbowls'));
		FashionBrand::create(array('name_en'=>'DESIGN WORKERS','slug'=>'design-workers'));
		FashionBrand::create(array('name_en'=>'ATAR','slug'=>'atar'));
		FashionBrand::create(array('name_en'=>'90Z','slug'=>'90z'));
		FashionBrand::create(array('name_en'=>'SAVANT SYNDROME','slug'=>'savant-syndrome'));
		FashionBrand::create(array('name_en'=>'JULIEBEANS','slug'=>'juliebeans'));
		FashionBrand::create(array('name_en'=>'VAIVATTOMASTI','slug'=>'vaivattomasti'));
		FashionBrand::create(array('name_en'=>'OUTSTANDINGORDINARY','slug'=>'outstandingordinary'));
		FashionBrand::create(array('name_en'=>'CRES.E.DIM.','slug'=>'cres-e-dim'));
		FashionBrand::create(array('name_en'=>'CUSTOMI','slug'=>'customi'));
		FashionBrand::create(array('name_en'=>'FRENK','slug'=>'frenk'));
		FashionBrand::create(array('name_en'=>'BURBERRY PROSUM','slug'=>'burberry-prosum'));
		FashionBrand::create(array('name_en'=>'THISISNEVERTHAT','slug'=>'thisisneverthat'));
		FashionBrand::create(array('name_en'=>'STUSSY','slug'=>'stussy'));
		FashionBrand::create(array('name_en'=>'BOY LONDON','slug'=>'boy-london'));
		FashionBrand::create(array('name_en'=>'CHROMEHEARTS','slug'=>'chromehearts'));
		FashionBrand::create(array('name_en'=>'THOM BROWNE','slug'=>'thom-browne'));
		FashionBrand::create(array('name_en'=>'SAINT LAURENT','slug'=>'saint-laurent'));
		FashionBrand::create(array('name_en'=>'MARTIN MARGIELA','slug'=>'martin-margiela'));
		FashionBrand::create(array('name_en'=>'NEW ERA','slug'=>'new-era'));
		FashionBrand::create(array('name_en'=>'ONITSUKA TIGER','slug'=>'onitsuka-tiger'));
		FashionBrand::create(array('name_en'=>'PATRON SAINT','slug'=>'patron-saint'));
		FashionBrand::create(array('name_en'=>'ZPLISH','slug'=>'zplish'));
		FashionBrand::create(array('name_en'=>'BRIXTON','slug'=>'brixton'));
		FashionBrand::create(array('name_en'=>'BRATSON','slug'=>'bratson'));
		FashionBrand::create(array('name_en'=>'R.SHEMISTE','slug'=>'r-shemiste'));
		FashionBrand::create(array('name_en'=>'JOY RICH','slug'=>'joy-rich'));
		FashionBrand::create(array('name_en'=>'SKECHERS','slug'=>'skechers'));
		FashionBrand::create(array('name_en'=>'DIOR HOMME','slug'=>'dior-homme'));
		FashionBrand::create(array('name_en'=>'PIERREHARDY','slug'=>'pierrehardy'));
		FashionBrand::create(array('name_en'=>'TEVA','slug'=>'teva'));
		FashionBrand::create(array('name_en'=>'CHACO','slug'=>'chaco'));
		FashionBrand::create(array('name_en'=>'CHUBASCO','slug'=>'chubasco'));
		FashionBrand::create(array('name_en'=>'ADIDAS ORIGINALS','slug'=>'adidas-originals'));
		FashionBrand::create(array('name_en'=>'AMI','slug'=>'ami'));
		FashionBrand::create(array('name_en'=>'BILLIONAIRE BOYS CLUB','slug'=>'billionaire-boys-club'));
		FashionBrand::create(array('name_en'=>'CLAE','slug'=>'clae'));
		FashionBrand::create(array('name_en'=>'HUF','slug'=>'huf'));
		FashionBrand::create(array('name_en'=>'KYE','slug'=>'kye'));
		FashionBrand::create(array('name_en'=>'MAISON KITSUNE','slug'=>'maison-kitsune'));
		FashionBrand::create(array('name_en'=>'OPENING CEREMONY','slug'=>'opening-ceremony'));
		FashionBrand::create(array('name_en'=>'CK JEANS','slug'=>'ck-jeans'));
		FashionBrand::create(array('name_en'=>'CRITIC','slug'=>'critic'));
		FashionBrand::create(array('name_en'=>'THOROGOOD','slug'=>'thorogood'));
		FashionBrand::create(array('name_en'=>'ALDEN','slug'=>'alden'));
		FashionBrand::create(array('name_en'=>'GRENSON','slug'=>'grenson'));
		FashionBrand::create(array('name_en'=>'REDWING','slug'=>'redwing'));
		FashionBrand::create(array('name_en'=>'LEATA','slug'=>'leata'));
		FashionBrand::create(array('name_en'=>'GRAM','slug'=>'gram'));
		FashionBrand::create(array('name_en'=>'LOAKE','slug'=>'loake'));
		FashionBrand::create(array('name_en'=>'PIECE MAKER','slug'=>'piece-maker'));
		FashionBrand::create(array('name_en'=>'HARE','slug'=>'hare'));
		FashionBrand::create(array('name_en'=>'CARVEN','slug'=>'carven'));
		FashionBrand::create(array('name_en'=>'3.1 PHILLIP LIM','slug'=>'3-1-phillip-lim'));
		FashionBrand::create(array('name_en'=>'PENFIELD','slug'=>'penfield'));
		FashionBrand::create(array('name_en'=>'KRAVITZ','slug'=>'kravitz'));
		FashionBrand::create(array('name_en'=>'CANADA GOOSE','slug'=>'canada-goose'));
		FashionBrand::create(array('name_en'=>'G SHOCK','slug'=>'g-shock'));
		FashionBrand::create(array('name_en'=>'JHONNYWEST','slug'=>'jhonnywest'));
		FashionBrand::create(array('name_en'=>'NIXON','slug'=>'nixon'));
		FashionBrand::create(array('name_en'=>'SUPREME','slug'=>'supreme'));
		FashionBrand::create(array('name_en'=>'NOIR','slug'=>'noir'));
		FashionBrand::create(array('name_en'=>'CANBYBI','slug'=>'canbybi'));
		FashionBrand::create(array('name_en'=>'BYBYO','slug'=>'bybyo'));
		FashionBrand::create(array('name_en'=>'WNDERKAMMER','slug'=>'wnderkammer'));
		FashionBrand::create(array('name_en'=>'RANGGAN','slug'=>'ranggan'));
		FashionBrand::create(array('name_en'=>'MISCHIEF','slug'=>'mischief'));
		FashionBrand::create(array('name_en'=>'LOOKAST','slug'=>'lookast'));
		FashionBrand::create(array('name_en'=>'MOSCA','slug'=>'mosca'));
		FashionBrand::create(array('name_en'=>'MUTEGARMENT','slug'=>'mutegarment'));
		FashionBrand::create(array('name_en'=>'MAN.G','slug'=>'man-g'));
		FashionBrand::create(array('name_en'=>'PALLADIUM','slug'=>'palladium'));
		FashionBrand::create(array('name_en'=>'IRONY PORN(O)','slug'=>'irony-porn-o'));
	}

}


class StreetMetaTableSeeder extends Seeder {

	public function run() {
		DB::table('street_meta')->delete();
		DB::table('street_meta')->truncate();

		StreetMeta::create(array('name_ko'=>'가로수길','slug'=>'garosu'));
		StreetMeta::create(array('name_ko'=>'홍대입구','slug'=>'hongdae'));
		StreetMeta::create(array('name_ko'=>'부산 서면','slug'=>'seomyeon'));
		StreetMeta::create(array('name_ko'=>'신주쿠','slug'=>'shinjuku'));
		StreetMeta::create(array('name_ko'=>'하라주쿠','slug'=>'harajuku'));
	}

}