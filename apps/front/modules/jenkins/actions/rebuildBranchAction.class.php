<?php

class rebuildBranchAction extends baseJenkinsAction
{

  /**
   * @param sfWebRequest $request
   *
   */
  public function execute($request)
  {
    $branchName = $request->getParameter('git_branch_slug');
    $userId     = $this->getUser()->getUserId();

    $groupRun   = JenkinsGroupRunPeer::retrieveBySfGuardUserIdAndGitBranchSlug($userId, $branchName);
    $this->forward404If(null === $groupRun, sprintf('Can\'t retrieve JenkinsGroupRun with branch name %s and user id %s', $branchName, $userId));

    $groupRun->rebuild($this->getJenkins(), $request->getParameter('delayed') == 1);
    JenkinsRunPeer::fillEmptyJobBuildNumber($this->getJenkins(), $this->getUser()->getUserId());

    $this->getUser()->setFlash('info', sprintf('The build [%s] has been relaunched', $groupRun->getLabel()));

    $this->redirect($this->generateUrl('branch_view', $groupRun));
  }

}
