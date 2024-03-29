<?php
class FibRgFcLcb97 
{
    public function __construct($stream, $reader, $shift = 0)
    {
        $fields = array('fcStshfOrig', 'lcbStshfOrig', 'fcStshf', 'lcbStshf', 'fcPlcffndRef', 
		'lcbPlcffndRef', 'fcPlcffndTxt','lcbPlcffndTxt', 'fcPlcfandRef', 'lcbPlcfandRef', 'fcPlcfandTxt', 
		'lcbPlcfandTxt', 'fcPlcfSed', 'lcbPlcfSed', 'fcPlcPad', 'lcbPlcPad', 'fcPlcfPhe',
		'lcbPlcfPhe', 'fcSttbfGlsy', 'lcbSttbfGlsy', 'fcPlcfGlsy', 'lcbPlcfGlsy', 'fcPlcfHdd', 
		'lcbPlcfHdd', 'fcPlcfBteChpx', 'lcbPlcfBteChpx', 'fcPlcfBtePapx', 'lcbPlcfBtePapx', 'fcPlcfSea', 
		'lcbPlcfSea', 'fcSttbfFfn', 'lcbSttbfFfn', 'fcPlcfFldMom', 'lcbPlcfFldMom', 'fcPlcfFldHdr', 
		'lcbPlcfFldHdr', 'fcPlcfFldFtn', 'lcbPlcfFldFtn', 'fcPlcfFldAtn', 'lcbPlcfFldAtn', 'fcPlcfFldMcr', 
		'lcbPlcfFldMcr', 'fcSttbfBkmk', 'lcbSttbfBkmk', 'fcPlcfBkf', 'lcbPlcfBkf', 'fcPlcfBkl', 
		'lcbPlcfBkl', 'fcCmds', 'lcbCmds', 'fcUnused1', 'lcbUnused1', 'fcSttbfMcr', 'lcbSttbfMcr',
		'fcPrDrvr', 'lcbPrDrvr', 'fcPrEnvPort', 'lcbPrEnvPort', 'fcPrEnvLand', 'lcbPrEnvLand', 'fcWss', 
		'lcbWss', 'fcDop', 'lcbDop', 'fcSttbfAssoc', 'lcbSttbfAssoc', 'fcClx', 'lcbClx', 'fcPlcfPgdFtn', 
		'lcbPlcfPgdFtn', 'fcAutosaveSource', 'lcbAutosaveSource', 'fcGrpXstAtnOwners', 'lcbGrpXstAtnOwners', 
		'fcSttbfAtnBkmk', 'lcbSttbfAtnBkmk', 'fcUnused2', 'lcbUnused2', 'fcUnused3', 'lcbUnused3', 
		'fcPlcSpaMom', 'lcbPlcSpaMom', 'fcPlcSpaHdr', 'lcbPlcSpaHdr', 'fcPlcfAtnBkf', 'lcbPlcfAtnBkf', 
		'fcPlcfAtnBkl', 'lcbPlcfAtnBkl', 'fcPms', 'lcbPms', 'fcFormFldSttbs', 'lcbFormFldSttbs', 'fcPlcfendRef', 
		'lcbPlcfendRef', 'fcPlcfendTxt', 'lcbPlcfendTxt', 'fcPlcfFldEdn', 'lcbPlcfFldEdn', 'fcUnused4', 
		'lcbUnused4', 'fcDggInfo', 'lcbDggInfo', 'fcSttbfRMark', 'lcbSttbfRMark', 'fcSttbfCaption', 'lcbSttbfCaption', 
		'fcSttbfAutoCaption', 'lcbSttbfAutoCaption', 'fcPlcfWkb', 'lcbPlcfWkb', 'fcPlcfSpl', 'lcbPlcfSpl', 
		'fcPlcftxbxTxt', 'lcbPlcftxbxTxt', 'fcPlcfFldTxbx', 'lcbPlcfFldTxbx', 'fcPlcfHdrtxbxTxt', 'lcbPlcfHdrtxbxTxt', 
		'fcPlcffldHdrTxbx', 'lcbPlcffldHdrTxbx', 'fcStwUser', 'lcbStwUser', 'fcSttbTtmbd', 'lcbSttbTtmbd', 
		'fcCookieData', 'lcbCookieData', 'fcPgdMotherOldOld', 'lcbPgdMotherOldOld', 'fcBkdMotherOldOld', 
		'lcbBkdMotherOldOld', 'fcPgdFtnOldOld', 'lcbPgdFtnOldOld', 'fcBkdFtnOldOld', 'lcbBkdFtnOldOld', 
		'fcPgdEdnOldOld', 'lcbPgdEdnOldOld', 'fcBkdEdnOldOld', 'lcbBkdEdnOldOld', 'fcSttbfIntlFld', 'lcbSttbfIntlFld', 
		'fcRouteSlip', 'lcbRouteSlip', 'fcSttbSavedBy', 'lcbSttbSavedBy', 'fcSttbFnm', 'lcbSttbFnm', 'fcPlfLst', 
		'lcbPlfLst', 'fcPlfLfo', 'lcbPlfLfo', 'fcPlcfTxbxBkd', 'lcbPlcfTxbxBkd', 'fcPlcfTxbxHdrBkd', 'lcbPlcfTxbxHdrBkd', 
		'fcDocUndoWord9', 'lcbDocUndoWord9', 'fcRgbUse', 'lcbRgbUse', 'fcUsp', 'lcbUsp', 'fcUskf', 'lcbUskf', 
		'lcbUskf', 'fcPlcupcRgbUse', 'lcbPlcupcRgbUse', 'fcPlcupcUsp', 'lcbPlcupcUsp', 'fcSttbGlsyStyle', 
		'lcbSttbGlsyStyle', 'fcPlgosl', 'lcbPlgosl', 'fcPlcocx', 'lcbPlcocx', 'fcPlcfBteLvc', 'lcbPlcfBteLvc', 
		'dwLowDateTime', 'dwHighDateTime', 'fcPlcfLvcPre10', 'lcbPlcfLvcPre10', 'fcPlcfAsumy', 'lcbPlcfAsumy', 
		'fcPlcfGram', 'lcbPlcfGram', 'fcSttbListNames', 'lcbSttbListNames', 'fcSttbfUssr', 'lcbSttbfUssr');
        
        for($i = 0; $i < count($fields); $i++) {
            $this->$fields[$i] = $reader->get($shift, 4, $stream);
            $shift += 4;
        }
    }
}
