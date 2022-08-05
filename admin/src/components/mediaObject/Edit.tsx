import {FunctionComponent} from "react";
import {MediaObject} from "../../types/MediaObject";
import {EditGuesser, InputGuesser} from "@api-platform/admin";
import {FunctionField} from "react-admin";
// @ts-ignore
import {ENTRYPOINT} from "../../config/entrypoint.ts";

interface Props {
    mediaObject: MediaObject;
}

export const Edit: FunctionComponent<Props> = ({mediaObject}) => (
    <EditGuesser {...mediaObject}>
        <InputGuesser source="name"/>
        <FunctionField
            label="Image"
            render={record => {return <img style={{ width:"100%", maxWidth: "500px", maxHeight: "500px"}} src={ENTRYPOINT + "/image/" + record.filePath} alt={record.name} />;}}
        />
    </EditGuesser>
);
