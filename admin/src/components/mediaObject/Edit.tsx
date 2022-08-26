import {FunctionComponent} from "react";
import {MediaObject} from "../../types/MediaObject";
import {EditGuesser, InputGuesser} from "@api-platform/admin";
import {FunctionField} from "react-admin";
// @ts-ignore
import {ENTRYPOINT} from "../../config/components/entrypoint.ts";

interface Props {
    mediaObject: MediaObject;
}

export const Edit: FunctionComponent<Props> = ({mediaObject}) => (
    <EditGuesser {...mediaObject}>
        <InputGuesser source="name"/>
        <FunctionField
            source="file"
            render={record => {return <img style={{ width:"100%", maxWidth: "300px", maxHeight: "300px"}} src={ENTRYPOINT + "/image/" + record.filePath} alt={record.name} />;}}
        />
    </EditGuesser>
);
