<?php
/*
	Project: PHP Typography
	Project URI: http://kingdesk.com/projects/php-typography/
	
	File modified to place pattern and exceptions in arrays that can be understood in php files.
	This file is released under the same copyright as the below referenced original file
	Original unmodified file is available at: http://mirror.unl.edu/ctan/language/hyph-utf8/tex/generic/hyph-utf8/patterns/
	Original file name: hyph-fr.tex
	
//============================================================================================================
	ORIGINAL FILE INFO

		% This file is part of hyph-utf8 package and resulted from
		% semi-manual conversions of hyphenation patterns into UTF-8 in June 2008.
		%
		% Source: frhyph.tex <2006-10-20>
		% Author: R. Bastian, D. Flipo, B. Gaulle <cesure-l at gutenberg.eu.org>
		%
		% The above mentioned file should become obsolete,
		% and the author of the original file should preferaby modify this file instead.
		%
		% Modificatios were needed in order to support native UTF-8 engines,
		% but functionality (hopefully) didn't change in any way, at least not intentionally.
		% This file is no longer stand-alone; at least for 8-bit engines
		% you probably want to use loadhyph-foo.tex (which will load this file) instead.
		%
		% Modifications were done by Jonathan Kew, Mojca Miklavec & Arthur Reutenauer
		% with help & support from:
		% - Karl Berry, who gave us free hands and all resources
		% - Taco Hoekwater, with useful macros
		% - Hans Hagen, who did the unicodifisation of patterns already long before
		%               and helped with testing, suggestions and bug reports
		% - Norbert Preining, who tested & integrated patterns into TeX Live
		%
		% However, the 'copyright/copyleft' owner of patterns remains the original author.
		%
		% The copyright statement of this file is thus:
		%
		%    Do with this file whatever needs to be done in future for the sake of
		%    'a better world' as long as you respect the copyright of original file.
		%    If you're the original author of patterns or taking over a new revolution,
		%    plese remove all of the TUG comments & credits that we added here -
		%    you are the Queen / the King, we are only the servants.
		%
		% If you want to change this file, rather than uploading directly to CTAN,
		% we would be grateful if you could send it to us (http://tug.org/tex-hyphen)
		% or ask for credentials for SVN repository and commit it yourself;
		% we will then upload the whole 'package' to CTAN.
		%
		% Before a new 'pattern-revolution' starts,
		% please try to follow some guidelines if possible:
		%
		% - \lccode is *forbidden*, and I really mean it
		% - all the patterns should be in UTF-8
		% - the only 'allowed' TeX commands in this file are: \patterns, \hyphenation,
		%   and if you really cannot do without, also \input and \message
		% - in particular, please no \catcode or \lccode changes,
		%   they belong to loadhyph-foo.tex,
		%   and no \lefthyphenmin and \righthyphenmin,
		%   they have no influence here and belong elsewhere
		% - \begingroup and/or \endinput is not needed
		% - feel free to do whatever you want inside comments
		%
		% We know that TeX is extremely powerful, but give a stupid parser
		% at least a chance to read your patterns.
		%
		% For more unformation see
		%
		%    http://tug.org/tex-hyphen
		%
		%------------------------------------------------------------------------------
		%
		% French hyphenation patterns
		%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
		% This file is available for free and can used and redistributed
		% asis for free. Modified versions should have another name.
		%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
		% \message{frhyph.tex - French hyphenation patterns (V2.12) <2002/12/11>}
		%
		%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
		%          *BEFORE* using this file *PLEASE* run checksum on it:           %
		%                       checksum -v frhyph.tex                             %
		% to make sure that it hasn't been damaged.                                %
		% Then if you notice anything wrong in french hyphenation please report to %
		% R. Bastian, D. Flipo, B. Gaulle at the email address:                    %
		%                                              cesure-l@gutenberg.eu.org   %
		%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
		%%      checksum        = '37208 1458 3078 34821'
		%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
		%%%%%%%% The most famous good guys who worked hard to obtain something usable. 
		% Jacques Desarmenien, Universite de Strasbourg :
		%          -  << how to run TeX in a French environment: hyphenation, fonts,
		%             typography. >> in Tugboat, 5 (1984) 91-102. and TeX85 conference
		%          -  << La division par ordinateur des mots francais :
		%             application a TeX >> in TSI vol. 5 No 4, 1986 (C) AFCET-
		%                                                             Gauthier-Villars
		% Norman Buckle, UQAH (nb; many additions)
		% Michael Ferguson, INRS-Telecommunications (mjf) June 1988
		% Justin Bur, Universite de Montreal (jbb; checked against original list)
		%                    all patterns including apostrophe missing from nb list
		% after that, GUTenberg  and specially Daniel Flipo and Bernard Gaulle
		% did their best effort to improve the list of patterns.
		%
		% -----------------------------------------------------------------
		%
		% Adaption of these patterns for
		%  - TeX Version 3.x and MLTeX 3.x (2.x)
		% and
		%  - all fonts in T1/`Cork' and/or CM/OT1 encoding
		% by Bernd Raichle 1996/08/28 (using ideas from `ghyph31.tex'
		% as of 1994-02-13 maintained by Bernd Raichle).
		% (An adaption for the old MLTeX 2.x exists but can not be
		% tested in lack of an executable.)
		%
		% -----------------------------------------------------------------


//============================================================================================================	
	
*/

$patgenLanguage = 'French';

$patgenExceptions = array();

$patgenMaxSeg = 13;

$patgen = array(
'begin'=>array(
'a'=>'04',
'â'=>'04',
'abréa'=>'003000',
'aesch'=>'003400',
'aminoac'=>'00000120',
'anastr'=>'0003400',
'antia'=>'000012',
'antie'=>'000012',
'antié'=>'000012',
'antienne'=>'000020000',
'antis'=>'000012',
'aposta'=>'0002300',
'asta'=>'00200',
'baisemain'=>'0002030000',
'biac'=>'00120',
'biat'=>'00120',
'biau'=>'00100',
'bioa'=>'00012',
'bisa'=>'00212',
'biu'=>'0012',
'ch'=>'004',
'chèvrefeuille'=>'00020030000000',
'cisalp'=>'0021000',
'con'=>'0004',
'cons'=>'00004',
'contresc'=>'000000120',
'contremaître'=>'0000003000000',
'coo'=>'0012',
'coolie'=>'0023000',
'cul'=>'0004',
'dacryoa'=>'00000012',
'ardent'=>'0030000',
'déa'=>'0012',
'déio'=>'00100',
'déo'=>'0012',
'dés'=>'0020',
'désacr'=>'0032300',
'désam'=>'000230',
'désatell'=>'003230000',
'désastr'=>'00320000',
'désc'=>'00320',
'désé'=>'00212',
'déségr'=>'0032300',
'désensib'=>'003200000',
'désert'=>'0032000',
'désexu'=>'0032000',
'dési'=>'00212',
'désid'=>'003230',
'désign'=>'0032300',
'désili'=>'0032300',
'désinen'=>'00323000',
'désinvo'=>'00320000',
'désir'=>'003230',
'désist'=>'0032000',
'désodé'=>'0032300',
'désœ'=>'00210',
'désol'=>'003230',
'désopil'=>'00323000',
'désorm'=>'0032000',
'désorp'=>'0032000',
'désoufr'=>'00320000',
'désp'=>'00320',
'dést'=>'00320',
'désun'=>'002120',
'diacé'=>'001200',
'diacid'=>'0012000',
'diald'=>'001000',
'diami'=>'001200',
'diatom'=>'0012000',
'dien'=>'00120',
'dish'=>'00230',
'dys'=>'0023',
'dysa'=>'00212',
'dysi'=>'00212',
'dyso'=>'00212',
'dysu'=>'00212',
'e'=>'04',
'ê'=>'04',
'é'=>'04',
'è'=>'04',
'ena'=>'0012',
'eno'=>'0012',
'eura'=>'00212',
'argent'=>'0030000',
'agnat'=>'023000',
'igné'=>'02300',
'igni'=>'02300',
'magnicide'=>'0023000000',
'magnificat'=>'00230000000',
'magnum'=>'0023000',
'prognath'=>'000230000',
'stagn'=>'000230',
'syngnath'=>'000230000',
'onguent'=>'00300000',
'i'=>'04',
'î'=>'04',
'still'=>'000030',
'ina'=>'0012',
'inanit'=>'0023000',
'inaugur'=>'00200000',
'ine'=>'0012',
'iné'=>'0012',
'ineffab'=>'00200000',
'inélucta'=>'002300000',
'inénarra'=>'002300000',
'inept'=>'002000',
'iner'=>'00200',
'inexora'=>'00200000',
'ini'=>'0012',
'inimiti'=>'00230000',
'iniq'=>'00230',
'init'=>'00230',
'ino'=>'0012',
'inocul'=>'0023000',
'inond'=>'002000',
'instab'=>'0012000',
'intera'=>'0000002',
'intere'=>'0000002',
'interé'=>'0000002',
'interi'=>'0000002',
'intero'=>'0000002',
'inter'=>'000043',
'interu'=>'0000002',
'inters'=>'0000002',
'inu'=>'0012',
'inuit'=>'002000',
'inul'=>'00230',
'kh'=>'004',
'talent'=>'0030000',
'dolent'=>'0030000',
'mack'=>'00230',
'macrosc'=>'00000120',
'maladres'=>'002120000',
'maladro'=>'00212000',
'malaisé'=>'00210000',
'malap'=>'002100',
'malav'=>'002120',
'malen'=>'002100',
'malint'=>'0021000',
'maloc'=>'002100',
'malod'=>'002120',
'marx'=>'00210',
'mégoh'=>'002100',
'mésa'=>'00200',
'mésan'=>'003000',
'méses'=>'002100',
'mési'=>'00210',
'mésus'=>'002120',
'métasta'=>'00001200',
'gemment'=>'00020000',
'comment'=>'00030000',
'sarment'=>'00030000',
'serment'=>'00030000',
'milliam'=>'00000100',
'monoa'=>'000012',
'monoe'=>'000012',
'monoé'=>'000012',
'monoi'=>'000012',
'monoïdé'=>'00001200',
'monoo'=>'000012',
'monou'=>'000012',
'monos'=>'000012',
'éminent'=>'00030000',
'nonobs'=>'0021000',
'o'=>'04',
'ô'=>'04',
'ouaou'=>'000100',
'ovisc'=>'000120',
'panaf'=>'002120',
'panamé'=>'0021200',
'panara'=>'0021200',
'panis'=>'002100',
'panoph'=>'0021200',
'panopt'=>'0021000',
'parache'=>'00212000',
'parachè'=>'00212000',
'paras'=>'000012',
'parhé'=>'002300',
'arpent'=>'0030000',
'penta'=>'000200',
'per'=>'0040',
'pera'=>'00012',
'pere'=>'00012',
'peré'=>'00012',
'peri'=>'00012',
'pero'=>'00012',
'peru'=>'00012',
'périos'=>'0000100',
'péris'=>'000012',
'périss'=>'0000230',
'pérista'=>'00002300',
'périu'=>'000012',
'ph'=>'004',
'phalanst'=>'000000320',
'pluria'=>'0000010',
'pontet'=>'0002000',
'posth'=>'000230',
'postin'=>'0002100',
'posto'=>'000212',
'postr'=>'000230',
'posts'=>'000012',
'préa'=>'00012',
'préala'=>'0002300',
'préau'=>'000200',
'préé'=>'00012',
'prée'=>'00012',
'préi'=>'00012',
'préo'=>'00012',
'préu'=>'00012',
'prés'=>'00012',
'proé'=>'00012',
'proscé'=>'0001200',
'proudh'=>'0000320',
'psychoan'=>'000000120',
'puddl'=>'000120',
'réa'=>'0012',
'réale'=>'002300',
'réalis'=>'0023000',
'réalit'=>'0023000',
'réaux'=>'002000',
'réé'=>'0012',
'rée'=>'0012',
'réel'=>'00200',
'réer'=>'00200',
'réèr'=>'00200',
'réi'=>'0012',
'réifi'=>'002300',
'réo'=>'0012',
'res'=>'0012',
'rescap'=>'0023000',
'rescisi'=>'00230000',
'resciso'=>'00230000',
'rescou'=>'0023000',
'rescri'=>'0023000',
'respect'=>'00230000',
'respir'=>'0023000',
'resplend'=>'002300000',
'respons'=>'00230000',
'resquil'=>'00230000',
'ress'=>'00230',
'rest'=>'00230',
'restab'=>'0034000',
'restag'=>'0034000',
'restand'=>'00340000',
'restat'=>'0034000',
'restén'=>'0034000',
'restér'=>'0034000',
'restim'=>'0034000',
'restip'=>'0034000',
'restoc'=>'0034000',
'restop'=>'0034000',
'restr'=>'003400',
'restrein'=>'004500000',
'restrict'=>'004500000',
'restrin'=>'00450000',
'restu'=>'003400',
'resty'=>'003400',
'réu'=>'0002',
'réuss'=>'002000',
'rétroa'=>'0000012',
'parent'=>'0030000',
'sch'=>'0004',
'ressent'=>'00030000',
'seule'=>'000200',
'sh'=>'004',
'suba'=>'00212',
'subalt'=>'0032000',
'subé'=>'00212',
'subér'=>'003230',
'subin'=>'002100',
'sublimin'=>'002300000',
'sublin'=>'0023000',
'sublu'=>'002300',
'subur'=>'002100',
'sura'=>'00212',
'surat'=>'003230',
'sure'=>'00212',
'sureau'=>'0032000',
'surell'=>'0032000',
'suret'=>'003200',
'suré'=>'00212',
'surh'=>'00230',
'surim'=>'002120',
'surinf'=>'0021000',
'surint'=>'0021000',
'surof'=>'002100',
'surox'=>'002100',
'latent'=>'0030000',
'patent'=>'0030000',
'th'=>'004',
'triac'=>'000120',
'trian'=>'000120',
'triat'=>'000120',
'trion'=>'000120',
'u'=>'04',
'û'=>'04',
'souvent'=>'00030000',
'y'=>'04'
),
'end'=>array(
'be'=>'400',
'bes'=>'4000',
'bent'=>'20000',
'ble'=>'4000',
'bles'=>'40000',
'blent'=>'200000',
'bre'=>'4000',
'bres'=>'40000',
'brent'=>'200000',
'ce'=>'400',
'ces'=>'4000',
'cent'=>'20000',
'jacent'=>'0030000',
'accent'=>'0030000',
'écent'=>'030000',
'munificent'=>'00000030000',
'réticent'=>'000030000',
'privatdocent'=>'0000000030000',
'innocent'=>'000030000',
'escent'=>'0030000',
'acquiescent'=>'000000040000',
'iscent'=>'0030000',
'immiscent'=>'0000040000',
'ch'=>'400',
'che'=>'4000',
'ches'=>'40000',
'chent'=>'200000',
'chle'=>'40000',
'chles'=>'400000',
'chre'=>'40000',
'chres'=>'400000',
'ck'=>'400',
'cke'=>'4000',
'ckes'=>'40000',
'ckent'=>'200000',
'cle'=>'4000',
'cles'=>'40000',
'clent'=>'200000',
'cre'=>'4000',
'cres'=>'40000',
'crent'=>'200000',
'de'=>'400',
'des'=>'4000',
'dent'=>'20000',
'décadent'=>'000030000',
'édent'=>'030000',
'ccident'=>'00030000',
'incident'=>'000030000',
'confident'=>'0000030000',
'trident'=>'00030000',
'dissident'=>'0000030000',
'chiendent'=>'0000030000',
'impudent'=>'000030000',
'prudent'=>'00030000',
'dlent'=>'200000',
'dre'=>'4000',
'dres'=>'40000',
'drent'=>'200000',
'fe'=>'400',
'fes'=>'4000',
'fent'=>'20000',
'fle'=>'4000',
'fles'=>'40000',
'flent'=>'200000',
'fre'=>'4000',
'fres'=>'40000',
'frent'=>'200000',
'ge'=>'400',
'ges'=>'4000',
'gent'=>'20000',
'régent'=>'0030000',
'entregent'=>'0000030000',
'indigent'=>'000030000',
'diligent'=>'000030000',
'intelligent'=>'000000030000',
'indulgent'=>'0000030000',
'tangent'=>'00030000',
'ringent'=>'00030000',
'contingent'=>'00000030000',
''argent'=>'00030000',
'sergent'=>'00030000',
'tergent'=>'00030000',
'résurgent'=>'0000030000',
'gle'=>'4000',
'gles'=>'40000',
'glent'=>'200000',
'gne'=>'4000',
'gnes'=>'40000',
'gnent'=>'200000',
'gre'=>'4000',
'gres'=>'40000',
'grent'=>'200000',
'gue'=>'4000',
'gues'=>'40000',
'guent'=>'200000',
''onguent'=>'000300000',
'he'=>'400',
'hes'=>'4000',
'je'=>'400',
'jes'=>'4000',
'jent'=>'20000',
'ke'=>'400',
'kes'=>'4000',
'kent'=>'20000',
'kh'=>'400',
'le'=>'400',
'les'=>'4000',
'lent'=>'20000',
'ivalent'=>'00030000',
'équivalent'=>'00000040000',
'monovalent'=>'00000030000',
'polyvalent'=>'00000030000',
'relent'=>'0030000',
'indolent'=>'000030000',
'insolent'=>'000030000',
'turbulent'=>'0000030000',
'succulent'=>'0000030000',
'féculent'=>'000030000',
'truculent'=>'0000030000',
'opulent'=>'00030000',
'corpulent'=>'0000030000',
'rulent'=>'0030000',
'sporulent'=>'0000040000',
'me'=>'400',
'mes'=>'4000',
'âment'=>'020000',
'dament'=>'0020000',
'fament'=>'0020000',
'amalgament'=>'00000020000',
'clament'=>'00020000',
'rament'=>'0020000',
'tempérament'=>'000000030000',
'tament'=>'0020000',
'testament'=>'0000030000',
'quament'=>'00020000',
'èment'=>'020000',
'carêment'=>'000020000',
'diaphragment'=>'0000000020000',
'rythment'=>'000020000',
'aiment'=>'0020000',
'raiment'=>'00030000',
'abîment'=>'00020000',
'éciment'=>'00020000',
'vidiment'=>'000020000',
'subliment'=>'0000020000',
'éliment'=>'00020000',
'reliment'=>'000020000',
'miment'=>'0020000',
'animent'=>'00020000',
'veniment'=>'000020000',
'riment'=>'0020000',
'détriment'=>'0000030000',
'nutriment'=>'0000030000',
'intiment'=>'000020000',
'estiment'=>'000020000',
'lment'=>'020000',
'flamment'=>'000020000',
'gramment'=>'000020000',
'omment'=>'0020000',
'ôment'=>'020000',
'slaloment'=>'0000020000',
'chroment'=>'000020000',
'toment'=>'0020000',
'arment'=>'0020000',
'erment'=>'0020000',
'antiferment'=>'000000030000',
'firment'=>'00020000',
'orment'=>'0020000',
'asment'=>'0020000',
'aument'=>'0020000',
'écument'=>'00020000',
'fument'=>'0020000',
'hument'=>'0020000',
'fichument'=>'0000030000',
'llument'=>'00020000',
'plument'=>'00020000',
'boument'=>'00020000',
'brument'=>'00020000',
'sument'=>'0020000',
'tument'=>'0020000',
'ne'=>'400',
'nes'=>'4000',
'nent'=>'20000',
'rémanent'=>'000030000',
'immanent'=>'000030000',
'permanent'=>'0000030000',
'prééminent'=>'00000030000',
'proéminent'=>'00000030000',
'suréminent'=>'00000030000',
'imminent'=>'000030000',
'continent'=>'0000030000',
'pertinent'=>'0000030000',
'abstinent'=>'0000030000',
'nsat'=>'03200',
'nsats'=>'032000',
'pe'=>'400',
'pes'=>'4000',
'pent'=>'20000',
'repent'=>'0030000',
''arpent'=>'00030000',
'serpent'=>'00030000',
'ph'=>'400',
'phe'=>'4000',
'phes'=>'40000',
'phent'=>'200000',
'phle'=>'40000',
'phles'=>'400000',
'phre'=>'40000',
'phres'=>'400000',
'ple'=>'4000',
'ples'=>'40000',
'plent'=>'200000',
'pre'=>'4000',
'pres'=>'40000',
'prent'=>'200000',
'que'=>'4000',
'ques'=>'40000',
'quent'=>'200000',
'équent'=>'0300000',
'éloquent'=>'000300000',
'grandiloquent'=>'00000000300000',
're'=>'400',
'res'=>'4000',
'rent'=>'20000',
'apparent'=>'000030000',
'transparent'=>'000000030000',
'érent'=>'030000',
'torrent'=>'00030000',
'current'=>'00030000',
'rhe'=>'4000',
'rhes'=>'40000',
'sch'=>'4000',
'sche'=>'40000',
'sches'=>'400000',
'se'=>'400',
'ses'=>'4000',
'sent'=>'20000',
'absent'=>'0030000',
'présent'=>'00030000',
'sh'=>'400',
'she'=>'4000',
'shes'=>'40000',
'shent'=>'200000',
'te'=>'400',
'tes'=>'4000',
'tent'=>'20000',
'compétent'=>'0000030000',
'énitent'=>'00030000',
'mécontent'=>'0000030000',
'omnipotent'=>'00000030000',
'ventripotent'=>'0000000030000',
'équipotent'=>'00000030000',
'impotent'=>'000030000',
'mittent'=>'00030000',
'th'=>'400',
'the'=>'4000',
'thes'=>'40000',
'thre'=>'40000',
'thres'=>'400000',
'tre'=>'4000',
'tres'=>'40000',
'trent'=>'200000',
've'=>'400',
'ves'=>'4000',
'vent'=>'20000',
'connivent'=>'0000030000',
'vre'=>'4000',
'vres'=>'40000',
'vrent'=>'200000',
'we'=>'400',
'wes'=>'4000',
'went'=>'20000',
'xent'=>'20000',
'ze'=>'400',
'zes'=>'4000',
'zent'=>'20000',
'privatdozent'=>'0000000030000'
),
'all'=>array(
'''=>'22',
''a'=>'004',
''â'=>'004',
'abh'=>'0020',
''abréa'=>'0003000',
'adh'=>'0020',
'aèdre'=>'012000',
''aesch'=>'0003400',
'alcool'=>'1000000',
'alalgi'=>'0210000',
''aminoac'=>'000000120',
''anastr'=>'00003400',
'anesthési'=>'1200000000',
''antia'=>'0000012',
''antie'=>'0000012',
''antienne'=>'0000020000',
''antié'=>'0000012',
''antis'=>'0000012',
''aposta'=>'00002300',
'apostr'=>'0002300',
'archiépis'=>'0000012000',
''asta'=>'000200',
'astro'=>'023000',
'ba'=>'100',
'bâ'=>'100',
'be'=>'100',
'bé'=>'100',
'bè'=>'100',
'bê'=>'100',
'bi'=>'100',
'bî'=>'100',
'bl'=>'120',
'bo'=>'100',
'bô'=>'100',
'br'=>'120',
'bu'=>'100',
'bû'=>'100',
'by'=>'100',
'ç'=>'10',
'ca'=>'100',
'câ'=>'100',
'caout'=>'003032',
'ce'=>'100',
'cé'=>'100',
'cè'=>'100',
'cê'=>'100',
'ch'=>'120',
'chb'=>'2000',
'chg'=>'2000',
'chl'=>'0020',
'chlorac'=>'00002320',
'chlorét'=>'00002320',
'chm'=>'2000',
'chn'=>'2000',
'chp'=>'2000',
'chr'=>'0020',
'chs'=>'2000',
'cht'=>'2000',
'chw'=>'2000',
'ci'=>'100',
'cî'=>'100',
'ck'=>'120',
'ckb'=>'2000',
'ckf'=>'2000',
'ckg'=>'2000',
'ckh'=>'2030',
'ckp'=>'2000',
'cks'=>'2000',
'ckt'=>'2000',
'cl'=>'120',
'co'=>'100',
'cô'=>'100',
'coacc'=>'001000',
'coacq'=>'001000',
'coad'=>'00120',
'coap'=>'00100',
'coar'=>'00100',
'coassoc'=>'00100000',
'coassur'=>'00100000',
'coau'=>'00100',
'coax'=>'00100',
'cœ'=>'100',
'coé'=>'0012',
'coef'=>'00100',
'coen'=>'00100',
'coex'=>'00100',
'conurb'=>'0020000',
'cr'=>'120',
'cu'=>'100',
'cû'=>'100',
'cy'=>'100',
'd''=>'100',
'da'=>'100',
'dâ'=>'100',
'ddh'=>'0120',
'de'=>'100',
'dé'=>'100',
'dè'=>'100',
'dê'=>'100',
'dhal'=>'32000',
'dhoud'=>'320000',
'di'=>'100',
'dî'=>'100',
'discop'=>'0023000',
'do'=>'100',
'dô'=>'100',
'dr'=>'120',
'ds'=>'012',
'du'=>'100',
'dû'=>'100',
'dy'=>'100',
''e'=>'004',
''ê'=>'004',
''é'=>'004',
''è'=>'004',
'édhi'=>'00200',
'édrie'=>'120000',
'édrique'=>'12000000',
'électr'=>'1200000',
'élément'=>'12000000',
''ena'=>'00012',
'énerg'=>'120000',
'enivr'=>'021200',
''eno'=>'00012',
'épiscop'=>'00023000',
'épiscope'=>'000340000',
'escop'=>'023000',
''eura'=>'000212',
'eustat'=>'0012000',
'extra'=>'000001',
'extrac'=>'0000020',
'extrai'=>'0000020',
'fa'=>'100',
'fâ'=>'100',
'fe'=>'100',
'fé'=>'100',
'fè'=>'100',
'fê'=>'100',
'fi'=>'100',
'fî'=>'100',
'fl'=>'120',
'fo'=>'100',
'fô'=>'100',
'fr'=>'120',
'fs'=>'012',
'fu'=>'100',
'fû'=>'100',
'fy'=>'100',
'ga'=>'100',
'gâ'=>'100',
'ge'=>'100',
'gé'=>'100',
'gè'=>'100',
'gê'=>'100',
'gha'=>'1200',
'ghe'=>'1200',
'ghi'=>'1200',
'gho'=>'1200',
'ghy'=>'1200',
'gi'=>'100',
'gî'=>'100',
'gl'=>'120',
'gn'=>'120',
''agnat'=>'0023000',
'agnos'=>'023000',
'cogniti'=>'00230000',
''igné'=>'002300',
''igni'=>'002300',
'ognomoni'=>'023000000',
'ognosi'=>'0230000',
'pugnable'=>'002300000',
'pugnac'=>'0023000',
'wagn'=>'00230',
'go'=>'100',
'gô'=>'100',
'gr'=>'120',
'gu'=>'100',
'gû'=>'100',
'gs'=>'012',
'gy'=>'100',
'ha'=>'100',
'hâ'=>'100',
'he'=>'100',
'hé'=>'100',
'hè'=>'100',
'hê'=>'100',
'hémié'=>'000010',
'hémopt'=>'0000120',
'hi'=>'100',
'hî'=>'100',
'ho'=>'100',
'hô'=>'100',
'hu'=>'100',
'hû'=>'100',
'hy'=>'100',
'hypera'=>'0000002',
'hypere'=>'0000002',
'hyperé'=>'0000002',
'hyperi'=>'0000002',
'hypero'=>'0000002',
'hypers'=>'0000002',
'hyper'=>'000041',
'hyperu'=>'0000002',
'hypoa'=>'000012',
'hypoe'=>'000012',
'hypoé'=>'000012',
'hypoi'=>'000012',
'hypoo'=>'000012',
'hypos'=>'000012',
'hypou'=>'000012',
''i'=>'004',
''î'=>'004',
'ialgi'=>'010000',
'iarthr'=>'0100000',
'ièdre'=>'012000',
'ill'=>'0020',
'cill'=>'00030',
'rcill'=>'000040',
'ucill'=>'000040',
'vacill'=>'0000040',
'gill'=>'00030',
'hill'=>'00030',
'lill'=>'00030',
'llion'=>'030000',
'mill'=>'00030',
'millet'=>'0004000',
'émill'=>'000040',
'semill'=>'0000040',
'rmill'=>'000040',
'armill'=>'0000050',
'capill'=>'0000030',
'papilla'=>'00000300',
'papille'=>'00000300',
'papilli'=>'00000300',
'papillom'=>'000003000',
'pupill'=>'0000030',
'pirill'=>'0000030',
'thrill'=>'0000030',
'cyrill'=>'0000030',
'ibrill'=>'0000030',
'pusill'=>'0000030',
'distill'=>'00000030',
'instill'=>'00000030',
'fritill'=>'00000030',
'boutill'=>'00000030',
'vanillin'=>'000003000',
'vanillis'=>'000003000',
'vill'=>'00030',
'avill'=>'000040',
'chevill'=>'00000040',
'uevill'=>'0000040',
'uvill'=>'000040',
'xill'=>'00030',
'informat'=>'100000000',
''ina'=>'00012',
''inanit'=>'00023000',
''inaugur'=>'000200000',
''ine'=>'00012',
''iné'=>'00012',
''ineffab'=>'000200000',
''inélucta'=>'0002300000',
''inénarra'=>'0002300000',
''inept'=>'0002000',
''iner'=>'000200',
''inexora'=>'000200000',
''ini'=>'00012',
''inimiti'=>'000230000',
''iniq'=>'000230',
''init'=>'000230',
''ino'=>'00012',
''inocul'=>'00023000',
''inond'=>'0002000',
''instab'=>'00012000',
''inter'=>'0000043',
''intera'=>'00000002',
''intere'=>'00000002',
''interé'=>'00000002',
''interi'=>'00000002',
''intero'=>'00000002',
''interu'=>'00000002',
''inters'=>'00000002',
''inu'=>'00012',
''inuit'=>'0002000',
''inul'=>'000230',
'ioact'=>'001200',
'ioxy'=>'01000',
'istat'=>'012000',
'j'=>'10',
'jk'=>'200',
'ka'=>'100',
'kâ'=>'100',
'ke'=>'100',
'ké'=>'100',
'kè'=>'100',
'kê'=>'100',
'kh'=>'120',
'ki'=>'100',
'kî'=>'100',
'ko'=>'100',
'kô'=>'100',
'kr'=>'120',
'ku'=>'100',
'kû'=>'100',
'ky'=>'100',
'la'=>'100',
'lâ'=>'100',
'là'=>'100',
'lawre'=>'002300',
'le'=>'100',
'lé'=>'100',
'lè'=>'100',
'lê'=>'100',
'li'=>'100',
'lî'=>'100',
'lo'=>'100',
'lô'=>'100',
'lst'=>'0120',
'lu'=>'100',
'lû'=>'100',
'ly'=>'100',
'ma'=>'100',
'mâ'=>'100',
'me'=>'100',
'mé'=>'100',
'mè'=>'100',
'mê'=>'100',
'mi'=>'100',
'mî'=>'100',
'mnémo'=>'120000',
'mnès'=>'12000',
'mnési'=>'120000',
'mo'=>'100',
'mô'=>'100',
'mœ'=>'100',
'montréal'=>'000230000',
'ms'=>'012',
'mu'=>'100',
'mû'=>'100',
'my'=>'100',
'moyenâg'=>'00002120',
'na'=>'100',
'nâ'=>'100',
'ne'=>'100',
'né'=>'100',
'nè'=>'100',
'nê'=>'100',
'ni'=>'100',
'nî'=>'100',
'no'=>'100',
'nô'=>'100',
'nœ'=>'100',
'nu'=>'100',
'nû'=>'100',
'nx'=>'010',
'ny'=>'100',
''o'=>'004',
''ô'=>'004',
'oblong'=>'0230000',
'octet'=>'100000',
'odl'=>'0120',
'oèdre'=>'012000',
'oioni'=>'010000',
'ombuds'=>'0000023',
'omnis'=>'000012',
'ostas'=>'012000',
'ostat'=>'012000',
'ostéro'=>'0120000',
'ostim'=>'012000',
'ostom'=>'012000',
'ostrad'=>'0120000',
'ostratu'=>'01200000',
'ostriction'=>'01200000000',
''ouaou'=>'0000100',
''ovisc'=>'0000120',
'oxya'=>'00012',
'pa'=>'100',
'pâ'=>'100',
'paléoé'=>'0000012',
'pe'=>'100',
'pé'=>'100',
'pè'=>'100',
'pê'=>'100',
'perh'=>'00030',
'pénul'=>'002000',
'péréq'=>'001220',
'ph'=>'120',
'phl'=>'0020',
'phn'=>'2000',
'photos'=>'0000012',
'phr'=>'0020',
'phs'=>'2000',
'pht'=>'2000',
'phtalé'=>'3020000',
'phtis'=>'302000',
'pi'=>'100',
'pî'=>'100',
'pl'=>'120',
'pné'=>'1200',
'pneu'=>'12000',
'po'=>'100',
'pô'=>'100',
'poastre'=>'00100000',
'polya'=>'000012',
'polye'=>'000012',
'polyé'=>'000012',
'polyè'=>'000012',
'polyi'=>'000012',
'polyo'=>'000012',
'polys'=>'000012',
'polyu'=>'000012',
'pr'=>'120',
'prostat'=>'00023000',
'psych'=>'120000',
'ptèr'=>'12000',
'ptér'=>'12000',
'pu'=>'100',
'pû'=>'100',
'py'=>'100',
'q'=>'10',
'ra'=>'100',
'râ'=>'100',
'radioa'=>'0000012',
're'=>'100',
'ré'=>'100',
'rè'=>'100',
'rê'=>'100',
'rh'=>'120',
'rheur'=>'230000',
'rhydr'=>'230000',
'ri'=>'100',
'rî'=>'100',
'ro'=>'100',
'rô'=>'100',
'ru'=>'100',
'rû'=>'100',
'ry'=>'100',
'sa'=>'100',
'sâ'=>'100',
'scaph'=>'120000',
'sclér'=>'120000',
'scop'=>'12000',
'sch'=>'1200',
'esch'=>'02300',
'isché'=>'023000',
'ischia'=>'0230000',
'ischio'=>'0230000',
'schs'=>'20000',
'se'=>'100',
'sé'=>'100',
'sè'=>'100',
'sê'=>'100',
'sesquia'=>'00000012',
'sh'=>'120',
'shm'=>'2000',
'shom'=>'23000',
'shr'=>'2000',
'shs'=>'2000',
'si'=>'100',
'sî'=>'100',
'slav'=>'12000',
'slov'=>'12000',
'so'=>'100',
'sô'=>'100',
'sœ'=>'100',
'spatia'=>'1200000',
'sperm'=>'120000',
'spor'=>'12000',
'sphèr'=>'120000',
'sphér'=>'120000',
'spiel'=>'120000',
'spiros'=>'1200000',
'standard'=>'120000000',
'stein'=>'120000',
'stéréos'=>'00000012',
'stigm'=>'120000',
'stock'=>'120000',
'stomos'=>'1200000',
'stroph'=>'1200000',
'structu'=>'12000000',
'style'=>'120000',
'su'=>'100',
'sû'=>'100',
'subs'=>'00012',
'supero'=>'0000002',
'super'=>'000041',
'supers'=>'0000002',
'surah'=>'003200',
'sy'=>'100',
'ta'=>'100',
'tâ'=>'100',
'tà'=>'100',
'tachya'=>'0000012',
'tchint'=>'0000032',
'te'=>'100',
'té'=>'100',
'tè'=>'100',
'tê'=>'100',
'télée'=>'000012',
'téléi'=>'000012',
'téléob'=>'0000120',
'téléop'=>'0000120',
'télés'=>'000012',
'th'=>'120',
'thermos'=>'00000012',
'theur'=>'230000',
'thl'=>'2000',
'thm'=>'2000',
'thn'=>'2000',
'thr'=>'0020',
'ths'=>'2000',
'ti'=>'100',
'tî'=>'100',
'to'=>'100',
'tô'=>'100',
'tr'=>'120',
'transa'=>'0000212',
'transact'=>'000032000',
'transats'=>'000032000',
'transh'=>'0000230',
'transo'=>'0000212',
'transp'=>'0000230',
'transu'=>'0000212',
'ttl'=>'0120',
'tu'=>'100',
'tû'=>'100',
'tungs'=>'000023',
'ty'=>'100',
''u'=>'004',
''û'=>'004',
'uniov'=>'000120',
'uniax'=>'000120',
'ustr'=>'02300',
'va'=>'100',
'vâ'=>'100',
've'=>'100',
'vé'=>'100',
'vè'=>'100',
'vê'=>'100',
'véloski'=>'00001200',
'vi'=>'100',
'vî'=>'100',
'vo'=>'100',
'vô'=>'100',
'voltamp'=>'00021000',
'vr'=>'120',
'vu'=>'100',
'vû'=>'100',
'vy'=>'100',
'wa'=>'100',
'we'=>'100',
'wi'=>'100',
'wo'=>'100',
'wu'=>'100',
'wr'=>'120',
''y'=>'004',
'yasth'=>'010000',
'ystom'=>'012000',
'yalgi'=>'010000',
'za'=>'100',
'ze'=>'100',
'zé'=>'100',
'zè'=>'100',
'zi'=>'100',
'zo'=>'100',
'zu'=>'100',
'zy'=>'100'
)
);

?>