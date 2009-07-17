<?php
/*
	Project: PHP Typography
	Project URI: http://kingdesk.com/projects/php-typography/
	
	
	File modified to place pattern and exceptions in arrays that can be understood in php files.
	This file is released under the same copyright as the below referenced original file
	Original unmodified file is available at: http://mirror.unl.edu/ctan/language/hyph-utf8/tex/generic/hyph-utf8/patterns/
	Original file name: hyph-ca.tex
	
//============================================================================================================
	ORIGINAL FILE INFO

		% This file is part of hyph-utf8 package and resulted from
		% semi-manual conversions of hyphenation patterns into UTF-8 in June 2008.
		%
		% Source: cahyph.tex (2003-09-08)
		% Author: Gonçal Badenes <g.badenes at ieee.org>
		%
		% The above mentioned file should become obsolete, 
		% and the author of the original file should preferaby modify this file instead.
		%
		% Modificatios were needed in order to support native UTF-8 engines, 
		% but functionality (hopefully) didn't change in any way,  at least not intentionally.
		% This file is no longer stand-alone; at least for 8-bit engines
		% you probably want to use loadhyph-foo.tex (which will load this file) instead.
		%
		% Modifications were done by Jonathan Kew,  Mojca Miklavec & Arthur Reutenauer
		% with help & support from:
		% - Karl Berry,  who gave us free hands and all resources
		% - Taco Hoekwater,  with useful macros
		% - Hans Hagen,  who did the unicodifisation of patterns already long before
		%               and helped with testing,  suggestions and bug reports
		% - Norbert Preining,  who tested & integrated patterns into TeX Live
		%
		% However,  the "copyright/copyleft" owner of patterns remains the original author.
		%
		% The copyright statement of this file is thus:
		%
		%    Do with this file whatever needs to be done in future for the sake of
		%    "a better world" as long as you respect the copyright of original file.
		%    If you're the original author of patterns or taking over a new revolution, 
		%    plese remove all of the TUG comments & credits that we added here -
		%    you are the Queen / the King,  we are only the servants.
		%
		% If you want to change this file,  rather than uploading directly to CTAN, 
		% we would be grateful if you could send it to us (http://tug.org/tex-hyphen)
		% or ask for credentials for SVN repository and commit it yourself;
		% we will then upload the whole "package" to CTAN.
		%
		% Before a new "pattern-revolution" starts, 
		% please try to follow some guidelines if possible:
		%
		% - \lccode is *forbidden*,  and I really mean it
		% - all the patterns should be in UTF-8
		% - the only "allowed" TeX commands in this file are: \patterns,  \hyphenation, 
		%   and if you really cannot do without,  also \input and \message
		% - in particular,  please no \catcode or \lccode changes, 
		%   they belong to loadhyph-foo.tex, 
		%   and no \lefthyphenmin and \righthyphenmin, 
		%   they have no influence here and belong elsewhere
		% - \begingroup and/or \endinput is not needed
		% - feel free to do whatever you want inside comments
		%
		% We know that TeX is extremely powerful,  but give a stupid parser
		% at least a chance to read your patterns.
		%
		% For more unformation see
		%
		%    http://tug.org/tex-hyphen
		%
		%------------------------------------------------------------------------------
		%
		% Hyphenation patterns for Catalan.
		% This is version 1.11
		% Compiled by Gonçal Badenes and Francina Turon, 
		%       December 1991-January 1995.
		%
		% Copyright (C) 1991-2003 Gonçal Badenes
		%
		% -----------------------------------------------------------------
		% IMPORTANT NOTICE:
		%
		% This program can be redistributed and/or modified under the terms
		% of the LaTeX Project Public License Distributed from CTAN
		% archives in directory macros/latex/base/lppl.txt; either
		% version 1 of the License,  or any later version.
		% -----------------------------------------------------------------
		%
		%%% ====================================================================
		%%%  @TeX-hyphen-file{
		%%%     author          = "Gonçal Badenes", 
		%%%     version         = "1.11", 
		%%%     date            = "15 July 2003", 
		%%%     time            = "15:08:12 CET", 
		%%%     filename        = "cahyph.tex", 
		%%%     email           = "g.badenes@ieee.org", 
		%%%     codetable       = "UTF-8", 
		%%%     keywords        = "TeX,  hyphen,  catalan", 
		%%%     supported       = "yes", 
		%%%     abstract        = "Catalan hyphenation patterns", 
		%%%     docstring       = "This file contains the hyphenation patterns
		%%%                        for the catalan language", 
		%%%  }
		%%% ====================================================================
		%
		% NOTICE: Version 1.11 is identical to version 1.10 (issued on January 17, 
		%         1995) except for the updated copyright notice above.
		%
		% The macros used were created for ghyph31.tex by Bernd Raichle (see the
		% German hyphenation pattern files for further details)
		%
		% This patterns have been created using standard,  conservative
		% hyphenation rules for catalan. The results have refined running them
		% through patgen. In that way,  the number of hits has been increased.
		%
		% These rules produce no wrong patterns (Results checked against the
		% ``Diccionari Ortogr\`afic i de Pron\'uncia'',  Enciclop\`edia
		% Catalana. The percentage of valid hyphen misses is lower than 1%
		%
		% Some of the patterns below represent combinations that never
		% happen in Catalan. We have tried to keep them to a minimum.
		%
		% *** IMPORTANT ***
		% \lefthyphenmin and \righthyphenmin should be set to 2 and 2
		% respectively. If you set them below these values incorrect breaks
		% will happen (specially at the beginning of foreign words and words
		% which begin with some prefixes).
		% *** IMPORTANT ***
		%
		% Please report any problem you might have to the authors!!!
		%
		%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
		% \message{Catalan Hyphenation Patterns `cahyphen' Version 1.11 <2003/07/15>}
		%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//============================================================================================================	
	
*/

$patgenLanguage = "Catalan";

$patgenExceptions = array('curie'=>'cu-rie', 'curies'=>'cu-ries', 'geisha'=>'gei-sha', 'geishes'=>'gei-shes', 'gouache'=>'goua-che', 'gouaches'=>'goua-ches', 'hippy'=>'hip-py', 'hippies'=>'hip-pies', 'hobby'=>'hob-by', 'hobbies'=>'hob-bies', 'jeep'=>'jeep', 'jeeps'=>'jeeps', 'joule'=>'joule', 'joules'=>'joules', 'kleenex'=>'klee-nex', 'kleenexs'=>'klee-nexs', 'larghetti'=>'lar-ghet-ti', 'larghetto'=>'lar-ghet-to', 'lied'=>'lied', 'lieder'=>'lieder', 'nosaltres'=>'nos-al-tres', 'royalties'=>'ro-yal-ties', 'royalty'=>'ro-yal-ty', 'vosaltres'=>'vos-al-tres', 'whisky'=>'whis-ky', 'whiskies'=>'whis-kies');

$patgenMaxSeg = 10;

$patgen = array('begin'=>array('hia'=>'0020', 'hie'=>'0020', 'hio'=>'0020', 'hiu'=>'0020', 'hua'=>'0020', 'hue'=>'0020', 'hui'=>'0020', 'huo'=>'0020', 'iè'=>'020', 'iò'=>'020', 'uè'=>'020', 'uò'=>'020', 'hié'=>'0020', 'hió'=>'0020', 'hiú'=>'0020', 'hià'=>'0020', 'hiè'=>'0020', 'hiò'=>'0020', 'hué'=>'0020', 'huí'=>'0020', 'huó'=>'0020', 'huà'=>'0020', 'huè'=>'0020', 'huò'=>'0020', 'antihi'=>'0000002', 'ben'=>'0020', 'bes'=>'0020', 'bis'=>'0020', 'cap'=>'0020', 'cel'=>'0020', 'clar'=>'00020', 'coll'=>'00200', 'con'=>'0020', 'cor'=>'0020', 'des'=>'0020', 'dis'=>'0020', 'ena'=>'0030', 'hiper'=>'000020', 'hipermn'=>'00000020', 'inac'=>'00300', 'inad'=>'00300', 'inap'=>'00300', 'ines'=>'00300', 'ino'=>'0030', 'inter'=>'000020', 'mal'=>'0020', 'malthus'=>'00012000', 'pan'=>'0020', 'per'=>'0020', 'peri'=>'00300', 'post'=>'00020', 'psal'=>'00020', 'rebes'=>'000020', 'red'=>'0020', 'sub'=>'0020', 'subo'=>'00030', 'subdes'=>'0000020', 'super'=>'000020', 'trans'=>'000020', 'ch'=>'002', 'th'=>'002'), 'end'=>array('aisme'=>'010000', 'eisme'=>'010000', 'iisme'=>'010000', 'oisme'=>'010000', 'uisme'=>'010000', 'aista'=>'010000', 'eista'=>'010000', 'iista'=>'010000', 'oista'=>'010000', 'uista'=>'010000', 'aum'=>'0100', 'eum'=>'0100', 'ium'=>'0100', 'oum'=>'0100', 'uum'=>'0100'), 'all'=>array('ba'=>'100', 'be'=>'100', 'bi'=>'100', 'bo'=>'100', 'bu'=>'100', 'ca'=>'100', 'ce'=>'100', 'ci'=>'100', 'co'=>'100', 'cu'=>'100', 'da'=>'100', 'de'=>'100', 'di'=>'100', 'do'=>'100', 'du'=>'300', 'fa'=>'100', 'fe'=>'100', 'fi'=>'100', 'fo'=>'100', 'fu'=>'100', 'ga'=>'100', 'ge'=>'100', 'gi'=>'100', 'go'=>'100', 'gu'=>'100', 'ha'=>'100', 'he'=>'100', 'hi'=>'100', 'ho'=>'100', 'hu'=>'100', 'ja'=>'100', 'je'=>'100', 'ji'=>'100', 'jo'=>'100', 'ju'=>'100', 'la'=>'100', 'le'=>'100', 'li'=>'100', 'lo'=>'100', 'lu'=>'100', 'ma'=>'100', 'me'=>'100', 'mi'=>'100', 'mo'=>'100', 'mu'=>'100', 'na'=>'100', 'ne'=>'100', 'ni'=>'300', 'no'=>'100', 'nu'=>'100', 'pa'=>'100', 'pe'=>'300', 'pi'=>'300', 'po'=>'300', 'pu'=>'100', 'qu'=>'100', 'ra'=>'100', 're'=>'100', 'ri'=>'100', 'ro'=>'100', 'ru'=>'100', 'sa'=>'100', 'se'=>'100', 'si'=>'100', 'so'=>'100', 'su'=>'100', 'ta'=>'100', 'te'=>'100', 'ti'=>'100', 'to'=>'100', 'tu'=>'100', 'va'=>'100', 've'=>'100', 'vi'=>'100', 'vo'=>'100', 'vu'=>'100', 'xa'=>'100', 'xe'=>'100', 'xi'=>'100', 'xo'=>'100', 'xu'=>'100', 'za'=>'100', 'ze'=>'100', 'zi'=>'100', 'zo'=>'100', 'zu'=>'100', 'bé'=>'100', 'bí'=>'100', 'bó'=>'100', 'bú'=>'100', 'bà'=>'100', 'bè'=>'100', 'bò'=>'100', 'cé'=>'100', 'cí'=>'100', 'có'=>'100', 'cú'=>'100', 'cà'=>'100', 'cè'=>'100', 'cò'=>'100', 'ço'=>'100', 'ça'=>'100', 'çu'=>'100', 'çó'=>'100', 'çú'=>'100', 'çà'=>'100', 'çò'=>'100', 'dé'=>'100', 'dí'=>'100', 'dó'=>'100', 'dú'=>'100', 'dà'=>'100', 'dè'=>'100', 'dò'=>'100', 'fé'=>'100', 'fí'=>'100', 'fó'=>'100', 'fú'=>'100', 'fà'=>'100', 'fè'=>'100', 'fò'=>'100', 'gé'=>'100', 'gí'=>'100', 'gó'=>'100', 'gú'=>'100', 'gà'=>'100', 'gè'=>'100', 'gò'=>'100', 'gü'=>'100', 'hé'=>'100', 'hí'=>'100', 'hó'=>'100', 'hú'=>'100', 'hà'=>'100', 'hè'=>'100', 'hò'=>'100', 'jé'=>'100', 'jí'=>'100', 'jó'=>'100', 'jú'=>'100', 'jà'=>'100', 'jè'=>'100', 'jò'=>'100', 'lé'=>'100', 'lí'=>'100', 'ló'=>'100', 'lú'=>'100', 'là'=>'100', 'lè'=>'100', 'lò'=>'100', 'mé'=>'100', 'mí'=>'100', 'mó'=>'100', 'mú'=>'100', 'mà'=>'100', 'mè'=>'100', 'mò'=>'100', 'né'=>'100', 'ní'=>'100', 'nó'=>'100', 'nú'=>'100', 'nà'=>'100', 'nè'=>'100', 'nò'=>'100', 'pé'=>'100', 'pí'=>'100', 'pó'=>'100', 'pú'=>'100', 'pà'=>'100', 'pè'=>'100', 'pò'=>'100', 'qü'=>'100', 'ré'=>'100', 'rí'=>'100', 'ró'=>'100', 'rú'=>'100', 'rà'=>'100', 'rè'=>'100', 'rò'=>'100', 'sé'=>'100', 'sí'=>'100', 'só'=>'100', 'sú'=>'100', 'sà'=>'100', 'sè'=>'100', 'sò'=>'100', 'té'=>'100', 'tí'=>'100', 'tó'=>'100', 'tú'=>'100', 'tà'=>'100', 'tè'=>'100', 'tò'=>'100', 'vé'=>'100', 'ví'=>'100', 'vó'=>'100', 'vú'=>'100', 'và'=>'100', 'vè'=>'100', 'vò'=>'100', 'xé'=>'100', 'xí'=>'100', 'xó'=>'100', 'xú'=>'100', 'xà'=>'100', 'xè'=>'100', 'xò'=>'100', 'zé'=>'100', 'zí'=>'100', 'zó'=>'100', 'zú'=>'100', 'zà'=>'100', 'zè'=>'100', 'zò'=>'100', 'lla'=>'3200', 'lle'=>'1200', 'lli'=>'1200', 'llo'=>'3200', 'llu'=>'1200', 'bla'=>'1200', 'ble'=>'1200', 'bli'=>'1200', 'blo'=>'1200', 'blu'=>'1200', 'bra'=>'1200', 'bre'=>'1200', 'bri'=>'1200', 'bro'=>'1200', 'bru'=>'1200', 'cla'=>'1200', 'cle'=>'1200', 'cli'=>'1200', 'clo'=>'1200', 'clu'=>'1200', 'cra'=>'1200', 'cre'=>'1200', 'cri'=>'1200', 'cro'=>'1200', 'cru'=>'1200', 'dra'=>'1200', 'dre'=>'1200', 'dri'=>'1200', 'dro'=>'1200', 'dru'=>'1200', 'fla'=>'1200', 'fle'=>'1200', 'fli'=>'1200', 'flo'=>'1200', 'flu'=>'1200', 'fra'=>'1200', 'fre'=>'1200', 'fri'=>'1200', 'fro'=>'1200', 'fru'=>'1200', 'gla'=>'1200', 'gle'=>'1200', 'gli'=>'1200', 'glo'=>'1200', 'glu'=>'1200', 'gra'=>'1200', 'gre'=>'1200', 'gri'=>'1200', 'gro'=>'1200', 'gru'=>'1200', 'pla'=>'1200', 'ple'=>'1200', 'pli'=>'1200', 'plo'=>'1200', 'plu'=>'1200', 'pra'=>'1200', 'pre'=>'1200', 'pri'=>'1200', 'pro'=>'1200', 'pru'=>'1200', 'tra'=>'1200', 'tre'=>'1200', 'tri'=>'1200', 'tro'=>'1200', 'tru'=>'1200', 'nya'=>'1200', 'nye'=>'1200', 'nyi'=>'1200', 'nyo'=>'1200', 'nyu'=>'1200', 'llé'=>'1200', 'llí'=>'1200', 'lló'=>'1200', 'llú'=>'1200', 'llà'=>'1200', 'llè'=>'1200', 'llò'=>'1200', 'blé'=>'1200', 'blí'=>'1200', 'bló'=>'1200', 'blú'=>'1200', 'blà'=>'1200', 'blè'=>'1200', 'blò'=>'1200', 'bré'=>'1200', 'brí'=>'1200', 'bró'=>'1200', 'brú'=>'1200', 'brà'=>'1200', 'brè'=>'1200', 'brò'=>'1200', 'clé'=>'1200', 'clí'=>'1200', 'cló'=>'1200', 'clú'=>'1200', 'clà'=>'1200', 'clè'=>'1200', 'clò'=>'1200', 'cré'=>'1200', 'crí'=>'1200', 'cró'=>'1200', 'crú'=>'1200', 'crà'=>'1200', 'crè'=>'1200', 'crò'=>'1200', 'dré'=>'1200', 'drí'=>'1200', 'dró'=>'1200', 'drú'=>'1200', 'drà'=>'1200', 'drè'=>'1200', 'drò'=>'1200', 'flé'=>'1200', 'flí'=>'1200', 'fló'=>'1200', 'flú'=>'1200', 'flà'=>'1200', 'flè'=>'1200', 'flò'=>'1200', 'fré'=>'1200', 'frí'=>'1200', 'fró'=>'1200', 'frú'=>'1200', 'frà'=>'1200', 'frè'=>'1200', 'frò'=>'1200', 'glé'=>'1200', 'glí'=>'1200', 'gló'=>'1200', 'glú'=>'1200', 'glà'=>'1200', 'glè'=>'1200', 'glò'=>'1200', 'gré'=>'1200', 'grí'=>'1200', 'gró'=>'1200', 'grú'=>'1200', 'grà'=>'1200', 'grè'=>'1200', 'grò'=>'1200', 'plé'=>'1200', 'plí'=>'1200', 'pló'=>'1200', 'plú'=>'1200', 'plà'=>'1200', 'plè'=>'1200', 'plò'=>'1200', 'pré'=>'1200', 'prí'=>'1200', 'pró'=>'1200', 'prú'=>'1200', 'prà'=>'1200', 'prè'=>'1200', 'prò'=>'1200', 'tré'=>'1200', 'trí'=>'1200', 'tró'=>'1200', 'trú'=>'1200', 'trà'=>'1200', 'trè'=>'1200', 'trò'=>'1200', 'nyé'=>'1200', 'nyí'=>'1200', 'nyó'=>'1200', 'nyú'=>'1200', 'nyà'=>'1200', 'nyè'=>'1200', 'nyò'=>'1200', 'aa'=>'010', 'ae'=>'010', 'ao'=>'010', 'ea'=>'010', 'ee'=>'010', 'eo'=>'010', 'ia'=>'010', 'ie'=>'010', 'io'=>'010', 'oa'=>'010', 'oe'=>'010', 'oo'=>'010', 'ua'=>'010', 'ue'=>'010', 'uo'=>'010', 'aé'=>'010', 'aí'=>'010', 'aó'=>'010', 'aú'=>'010', 'aà'=>'010', 'aè'=>'010', 'aò'=>'010', 'aï'=>'010', 'aü'=>'010', 'eé'=>'010', 'eí'=>'010', 'eó'=>'010', 'eú'=>'010', 'eà'=>'010', 'eè'=>'010', 'eò'=>'010', 'eï'=>'010', 'eü'=>'010', 'ié'=>'010', 'ií'=>'010', 'ió'=>'010', 'iú'=>'010', 'ià'=>'010', 'iè'=>'010', 'iò'=>'010', 'iï'=>'010', 'iü'=>'010', 'oé'=>'010', 'oí'=>'010', 'oó'=>'010', 'oú'=>'010', 'oà'=>'010', 'oè'=>'010', 'oò'=>'010', 'oï'=>'010', 'oü'=>'010', 'ué'=>'010', 'uí'=>'010', 'uó'=>'010', 'uú'=>'010', 'uà'=>'010', 'uè'=>'010', 'uò'=>'010', 'uï'=>'010', 'uü'=>'010', 'éa'=>'010', 'ée'=>'010', 'éo'=>'010', 'éï'=>'010', 'éü'=>'010', 'ía'=>'010', 'íe'=>'010', 'ío'=>'010', 'íï'=>'010', 'íü'=>'010', 'óa'=>'010', 'óe'=>'010', 'óo'=>'010', 'óï'=>'010', 'óü'=>'010', 'úa'=>'010', 'úe'=>'010', 'úo'=>'010', 'úï'=>'010', 'úü'=>'010', 'àa'=>'010', 'àe'=>'010', 'ào'=>'010', 'àï'=>'010', 'àü'=>'010', 'èa'=>'010', 'èe'=>'010', 'èo'=>'010', 'èï'=>'010', 'èü'=>'010', 'òa'=>'010', 'òe'=>'010', 'òo'=>'010', 'òï'=>'010', 'òü'=>'010', 'ïa'=>'010', 'ïe'=>'010', 'ïo'=>'010', 'ïé'=>'010', 'ïí'=>'010', 'ïó'=>'010', 'ïú'=>'010', 'ïà'=>'010', 'ïè'=>'010', 'ïò'=>'010', 'ïi'=>'010', 'üa'=>'010', 'üe'=>'010', 'üo'=>'010', 'üé'=>'010', 'üí'=>'010', 'üó'=>'010', 'üú'=>'010', 'üà'=>'010', 'üè'=>'010', 'üò'=>'010', 'aia'=>'0120', 'aie'=>'0120', 'aio'=>'0120', 'aiu'=>'0120', 'aua'=>'0120', 'aue'=>'0120', 'aui'=>'0120', 'auo'=>'0120', 'auu'=>'0120', 'eia'=>'0120', 'eie'=>'0120', 'eio'=>'0120', 'eiu'=>'0120', 'eua'=>'0120', 'eue'=>'0120', 'eui'=>'0120', 'euo'=>'0120', 'euu'=>'0120', 'iia'=>'0120', 'iie'=>'0120', 'iio'=>'0120', 'iiu'=>'0120', 'iua'=>'0120', 'iue'=>'0120', 'iui'=>'0120', 'iuo'=>'0120', 'iuu'=>'0120', 'oia'=>'0120', 'oie'=>'0120', 'oio'=>'0120', 'oiu'=>'0120', 'oua'=>'0120', 'oue'=>'0120', 'ouo'=>'0120', 'oui'=>'0120', 'ouu'=>'0120', 'uia'=>'0120', 'uie'=>'0120', 'uio'=>'0120', 'uiu'=>'0120', 'uua'=>'0120', 'uue'=>'0120', 'uui'=>'0120', 'uuo'=>'0120', 'uuu'=>'0120', 'aié'=>'0120', 'aií'=>'0120', 'aió'=>'0120', 'aiú'=>'0120', 'aià'=>'0120', 'aiè'=>'0120', 'aiò'=>'0120', 'aué'=>'0120', 'auí'=>'0120', 'auó'=>'0120', 'auú'=>'0120', 'auà'=>'0120', 'auè'=>'0120', 'auò'=>'0120', 'eié'=>'0120', 'eií'=>'0120', 'eió'=>'0120', 'eiú'=>'0120', 'eià'=>'0120', 'eiè'=>'0120', 'eiò'=>'0120', 'eué'=>'0120', 'euí'=>'0120', 'euó'=>'0120', 'euú'=>'0120', 'euà'=>'0120', 'euè'=>'0120', 'euò'=>'0120', 'iié'=>'0120', 'iií'=>'0120', 'iió'=>'0120', 'iiú'=>'0120', 'iià'=>'0120', 'iiè'=>'0120', 'iiò'=>'0120', 'iué'=>'0120', 'iuí'=>'0120', 'iuó'=>'0120', 'iuú'=>'0120', 'iuà'=>'0120', 'iuè'=>'0120', 'iuò'=>'0120', 'oié'=>'0120', 'oií'=>'0120', 'oió'=>'0120', 'oiú'=>'0120', 'oià'=>'0120', 'oiè'=>'0120', 'oiò'=>'0120', 'oué'=>'0120', 'ouí'=>'0120', 'ouó'=>'0120', 'ouú'=>'0120', 'ouà'=>'0120', 'ouè'=>'0120', 'ouò'=>'0120', 'uié'=>'0120', 'uií'=>'0120', 'uió'=>'0120', 'uiú'=>'0120', 'uià'=>'0120', 'uiè'=>'0120', 'uiò'=>'0120', 'uué'=>'0120', 'uuí'=>'0120', 'uuó'=>'0120', 'uuú'=>'0120', 'uuà'=>'0120', 'uuè'=>'0120', 'uuò'=>'0120', 'éia'=>'0120', 'éie'=>'0120', 'éio'=>'0120', 'éiu'=>'0120', 'éua'=>'0120', 'éue'=>'0120', 'éuo'=>'0120', 'éui'=>'0120', 'éuu'=>'0120', 'íia'=>'0120', 'íie'=>'0120', 'íio'=>'0120', 'íiu'=>'0120', 'íua'=>'0120', 'íue'=>'0120', 'íuo'=>'0120', 'íui'=>'0120', 'íuu'=>'0120', 'óia'=>'0120', 'óie'=>'0120', 'óio'=>'0120', 'óiu'=>'0120', 'óua'=>'0120', 'óue'=>'0120', 'óuo'=>'0120', 'óui'=>'0120', 'óuu'=>'0120', 'úia'=>'0120', 'úie'=>'0120', 'úio'=>'0120', 'úiu'=>'0120', 'úua'=>'0120', 'úue'=>'0120', 'úuo'=>'0120', 'úui'=>'0120', 'úuu'=>'0120', 'àia'=>'0120', 'àie'=>'0120', 'àio'=>'0120', 'àiu'=>'0120', 'àua'=>'0120', 'àue'=>'0120', 'àuo'=>'0120', 'àui'=>'0120', 'àuu'=>'0120', 'èia'=>'0120', 'èie'=>'0120', 'èio'=>'0120', 'èiu'=>'0120', 'èua'=>'0120', 'èue'=>'0120', 'èuo'=>'0120', 'èui'=>'0120', 'èuu'=>'0120', 'òia'=>'0120', 'òie'=>'0120', 'òio'=>'0120', 'òiu'=>'0120', 'òua'=>'0120', 'òue'=>'0120', 'òuo'=>'0120', 'òui'=>'0120', 'òuu'=>'0120', 'ïia'=>'0120', 'ïie'=>'0120', 'ïio'=>'0120', 'ïié'=>'0120', 'ïií'=>'0120', 'ïió'=>'0120', 'ïiú'=>'0120', 'ïià'=>'0120', 'ïiè'=>'0120', 'ïiò'=>'0120', 'ïiu'=>'0120', 'ïua'=>'0120', 'ïue'=>'0120', 'ïuo'=>'0120', 'ïué'=>'0120', 'ïuí'=>'0120', 'ïuó'=>'0120', 'ïuú'=>'0120', 'ïuà'=>'0120', 'ïuè'=>'0120', 'ïuò'=>'0120', 'ïui'=>'0120', 'ïuu'=>'0120', 'üia'=>'0120', 'üie'=>'0120', 'üio'=>'0120', 'üié'=>'0120', 'üií'=>'0120', 'üió'=>'0120', 'üiú'=>'0120', 'üià'=>'0120', 'üiè'=>'0120', 'üiò'=>'0120', 'üiu'=>'0120', 'üua'=>'0120', 'üue'=>'0120', 'üuo'=>'0120', 'üué'=>'0120', 'üuí'=>'0120', 'üuó'=>'0120', 'üuú'=>'0120', 'üuà'=>'0120', 'üuè'=>'0120', 'üuò'=>'0120', 'üui'=>'0120', 'üuu'=>'0120', 'gua'=>'0020', 'gue'=>'0020', 'gui'=>'0020', 'guo'=>'0020', 'qua'=>'0020', 'que'=>'0020', 'qui'=>'0020', 'quo'=>'0020', 'gué'=>'0020', 'guí'=>'0020', 'guó'=>'0020', 'guà'=>'0020', 'guè'=>'0020', 'guò'=>'0020', 'qué'=>'0020', 'quí'=>'0020', 'quó'=>'0020', 'quà'=>'0020', 'què'=>'0020', 'quò'=>'0020', 'güe'=>'0020', 'güé'=>'0020', 'güí'=>'0020', 'güè'=>'0020', 'güi'=>'0020', 'qüe'=>'0020', 'qüé'=>'0020', 'qüí'=>'0020', 'qüè'=>'0020', 'qüi'=>'0020', 'gno'=>'0200', 'psi'=>'0200', 'pse'=>'0200', 'pneu'=>'02000', 'gnò'=>'0200', 'psí'=>'0200', 'einstein'=>'000120000', 'rutherford'=>'00120000000', 'nietzsche'=>'0020010200', 'exp'=>'3000', 'nef'=>'3000', 'nei'=>'3000', 'pr'=>'300', 'ser'=>'3000', 'ane'=>'0300', 'ari'=>'0300', 'bise'=>'00300', 'desag'=>'000300', 'desar'=>'000300', 'desav'=>'000300', 'desenc'=>'0003000', 'eism'=>'03000', 'ele'=>'0300', 'erio'=>'03000', 'eris'=>'03000', 'esaco'=>'003000', 'esaf'=>'00300', 'esap'=>'00300', 'esarr'=>'003000', 'esas'=>'00300', 'esint'=>'003000', 'ign'=>'0030', 'inex'=>'00300', 'nsi'=>'0300', 'oro'=>'0300', 'quie'=>'00030', 'semp'=>'03000', 'sesp'=>'03000', 'suba'=>'00030', 'uiet'=>'00300', 'ognò'=>'03000'));
?>